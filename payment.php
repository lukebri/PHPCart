<?php
require "inc/db.php";
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}


// STEP 2: POST IPN data back to PayPal to validate

$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
// error_log("Got " . curl_error($ch) . " when processing IPN data");
  mail(adminEmail, 'paypalerror', "curlerror");
  curl_close($ch);
  exit;
}
curl_close($ch);


// STEP 3: Inspect IPN validation result and act accordingly
if (strcmp ($res, "VERIFIED") == 0) {
// The IPN is verified, process it:

// assign posted variables to local variables
  $payment_status = $_POST['payment_status'];
  $amount = $_POST['mc_gross'];
  $receiver_email = $_POST['receiver_email'];
  $payer_email = $_POST['payer_email'];
  $orderID = $_POST['invoice'];
  $address = "";
  $address .= $_POST['address_name']. "<br>";
  $address .= $_POST['address_street']. "<br>";
  $address .= $_POST['address_city']. "<br>";
  $address .= $_POST['address_state'] . " ";
  $address .= $_POST['address_zip']. "<br>";
  $address .= $_POST['address_country']. "<br>";


/////// Confirm the received amount matches the amount stored in database
  try {
    $query = $db->prepare("SELECT COUNT(`order_id`) FROM orders WHERE order_id = ? AND total = ?");
    $query->bindValue(1, $orderID, PDO::PARAM_INT);
    $query->bindValue(2, $amount, PDO::PARAM_INT);
    $query->execute();
    $rows = $query->fetchColumn();

    if($rows == 1){
      $valid = true;
// Now grab email accociated with order
      $query2 = $db->prepare("SELECT users.email
        FROM orders
        INNER JOIN users
        ON orders.user_id=users.id
        WHERE orders.order_id = ?
        ");
      $query2->bindValue(1, $orderID, PDO::PARAM_INT);
      $query2->execute();
      $row = $query2->fetch(PDO::FETCH_ASSOC);
      $userEmail = $row['email'];
    }else{
      $valid = false;
    }

  } catch(PDOException $e) {
    mail($userEmail, 'Paypal order error', "Something went wrong with your payment. Please contact us.");
    $valid = false;
  }
/// If valid, continue. Else stop script.
  if ($valid) {
// Add address for this order in database
    $stmt = $db->prepare('INSERT INTO address (order_id, name, street, city, state, zip, country) VALUES(:order_id, :name, :street, :city, :state, :zip, :country)');
    $stmt->bindParam(':order_id', $orderID, PDO::PARAM_INT);
    $stmt->bindParam(':name', $_POST['address_name'], PDO::PARAM_STR);
    $stmt->bindParam(':street', $_POST['address_street'], PDO::PARAM_STR);
    $stmt->bindParam(':city', $_POST['address_city'], PDO::PARAM_STR);
    $stmt->bindParam(':state', $_POST['address_state'], PDO::PARAM_STR);
    $stmt->bindParam(':zip', $_POST['address_zip'], PDO::PARAM_INT);
    $stmt->bindParam(':country', $_POST['address_country'], PDO::PARAM_STR);
    $stmt->execute();
  } else {
    mail($userEmail, 'Paypal order error', "Paypal payment doesn't match order details.");
    exit;
  }
  try {
    $stmt = $db->prepare('UPDATE orders SET status = :status WHERE order_id = :id');
    $stmt->execute(array(
      ':id'   => $orderID,
      ':status' => "paid"
      ));

    mail(adminEmail, 'An order has been paid.', "The following order has been paid: #$orderID");
    $to = $userEmail;
    $message = '
    <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Hakoona Order #' . $orderID . '</title>
    </head>
    <body style="background:#D8DEFF;">
      <p style="background: #D8DEFF;">Your payment has been confirmed.</p>
      <p>It will be shipped to the following address:<br>
        ' . $address . '
      </p>
      <p style="width:100%;color: rgb(73, 73, 73);background: rgb(217, 217, 255);">Total:$' . $amount . '</p>
    </body>
    </html>
    ';

// // subject
    $subject = "Hakoona Order # " . $orderID . " | Payment Received";

// To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
    $headers .= "Return-Path:<orders@hakkona.com.au>\r\n";
    $headers .= 'From: Hakoona <orders@hakkona.com.au>' . "\r\n";
// // Mail it
    mail($to, $subject, $message, $headers);
  } catch(PDOException $e) {
    mail(adminEmail, 'Paypal order error', "Error");
  }

} else if (strcmp ($res, "INVALID") == 0) {
// IPN invalid, log for manual investigation
  mail(adminEmail, 'paypalerror invalid', "Paypal IPN sent invalid response");
}


?>