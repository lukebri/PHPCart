<?php require_once 'db.php';
class Paypal {
  private $db;
  public function __construct($database) {
    $this->db = $database;
  }

/// This creates the order in the database when Paypal is used in checkout
  public function newOrder($data) {

    $userid = $data['id'];
    $prodids = $data['products']; // Products set via ajax in cartSave.php
    $paypalPrice = $data['pprice'];
    $total = $data['total'];
    if (!isset($_SESSION['token'])) { echo json_encode("Your session expired, please refresh.");
    exit;
  }
  else {
          unset($_SESSION['token']); // stop user resubmitting the form, JS empties cart
        }
        // First let's check if the total submitted matches the
      // Actual value of all the cart items submitted
        foreach ($prodids as $prod => $id) {
          $query = $this->db->prepare("SELECT `price` FROM `products` WHERE `id` = ?");
          $query->bindValue(1, $id, PDO::PARAM_INT);
          $query->execute();
          $retrieved = $query->fetch();
          $prices[] = $retrieved['price'];
        }

        // Now we know the total of all items entered, we compare
        $prices = array_sum($prices);
        if (intval($prices) !== intval($total) && intval($prices)) {
          mail(adminEmail, "Paypal Order Error", "Someone attempted to submit an order with the incorrect Paypal total");
          exit;
        }

      // Create the new order!
        try {
          $stmt = $this->db->prepare('INSERT INTO orders (user_id, total) VALUES(:userid, :total)');
          $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
          $stmt->bindValue(':total', $total, PDO::PARAM_INT);
          $stmt->execute();

      // Catch the ID of the column we've just entered
          $orderID = $this->db->lastInsertId();
      // Grab the email accociated with this ID
          $query2 = $this->db->prepare("SELECT users.email
            FROM orders
            INNER JOIN users
            ON orders.user_id=users.id
            WHERE orders.order_id = ?
            ");
          $query2->bindValue(1, $orderID, PDO::PARAM_INT);
          $query2->execute();
          $row = $query2->fetch(PDO::FETCH_ASSOC);
          $userEmail = $row['email'];

       //    Foreach $prodid we need to enter, we need to loop over our array and insert them

          $list = '';
          $total = 0;
          foreach($prodids as $key => $value) {
            $query = $this->db->prepare("SELECT * FROM `products` WHERE `id` = ? LIMIT 1");
            $query->bindValue(1, $value, PDO::PARAM_INT);
            $query->execute();
            $prod = $query->fetchAll(PDO::FETCH_ASSOC);
            $total += $prod{0}{'price'};
            $list .= '<li><b>$'.$prod{0}{'price'}.' </b>'
            . ' ' . $prod{0}{'name'}
            . '</li>';
          }

          foreach($prodids as $key => $prodid) {
            $stmt = $this->db->prepare('INSERT INTO product_orders (whichorder, product_id) VALUES(:orderID, :prodid)');
            $stmt->bindValue(':orderID', $orderID, PDO::PARAM_INT);
            $stmt->bindValue(':prodid', $prodid, PDO::PARAM_INT);
            $stmt->execute();
            $completed = true;
          }
          $email = $_SESSION['user']{'email'};
          $name = $_SESSION['user']{'username'};
          if ($completed) {
          // SELECT * FROM product_orders LEFT JOIN products ON products.id = product_orders.product_id // Might need this later

           $message = '
           <html>
           <head>
             <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
             <title>Hakoona Order #' . $orderID . '</title>
           </head>
           <body style="background:#D8DEFF;">
            <p style="background: #D8DEFF;">Thanks for ordering from us.</p>
            <ul>
             ' . $list . '
           </ul>
           <p style="width:100%;color: rgb(73, 73, 73);background: rgb(217, 217, 255);">Total:$' . $total . '</p>
           <p>You will receive a confirmation email when payment has been confirmed.</p>
         </body>
         </html>
         ';

        // subject
         $subject = "Hakoona Order # $orderID";

        // To send HTML mail, the Content-type header must be set
         $headers  = 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
         $headers .= "Return-Path:<sales@hakoona.net.au>\r\n";
         $headers .= 'From: Hakoona <sales@hakoona.net.au>' . "\r\n";
         $to = $userEmail;
        // Mail it
         mail($to, $subject, $message, $headers);

       } else {
        echo json_encode("InComplete");
        mail($email, 'cartcheck', "Something went wrong with your order. Please contact us.");
      }
    } catch(PDOException $e) {
      $message = "Results: " . print_r( $e, true );
      mail(adminEmail, 'Paypal order error', $message);
    }

    return $orderID;
  }

}
?>