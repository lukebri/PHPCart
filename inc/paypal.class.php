<?php
require_once 'db.php';

class Paypal {
  private $db;
  public function __construct($database) {
    $this->db = $database;
  }

/// Add order when Paypal button is clicked
  // [orders] > user_id {rest are default on creation[createdCURTIMESTAMP, statusDefault]}
  // FOREACH: [product_orders] > whichorder {accociated order ID}
  //                             product_id {which product}
  public function newOrder($data) {

    $userid = $data['id'];
                $prodids = $data['products']; // Products array posted from ajax in cartSave
                if (!isset($_SESSION['token'])) { echo json_encode("Your session expired, please refresh."); exit;
              }
              else {
                    unset($_SESSION['token']); // stop user resubmitting the form, JS empties cart
                  }
                // Create the new order!
                  try {
                    $stmt = $this->db->prepare('INSERT INTO orders (user_id) VALUES(:userid)');
                    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
                    $stmt->execute();

                // Catch the ID of the column we've just entered
                    $orderID = $this->db->lastInsertId();

                 //    Foreach $prodid we need to enter, we need to loop over our array and insert them



                    $list = '';


                    foreach($prodids as $key => $value) {
                      $query = $this->db->prepare("SELECT * FROM `products` WHERE `id` = ? LIMIT 1");
                      $query->bindValue(1, $value, PDO::PARAM_INT);
                      $query->execute();
                      $prod = $query->fetchAll(PDO::FETCH_ASSOC);
                      $total = $total + $prod{0}{'price'};
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
                     echo json_encode($orderID);
                    // SELECT * FROM product_orders LEFT JOIN products ON products.id = product_orders.product_id

                     $message = '
                     <html>
                     <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Test Order #' . $orderID . '</title>
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

// // subject
                   $subject = 'Test Order #';

// To send HTML mail, the Content-type header must be set
                   $headers  = 'MIME-Version: 1.0' . "\r\n";
                   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
                   $headers .= "Return-Path:<your@email.com>\r\n";
                   $headers .= 'From: TestSubject <your@email.com>' . "\r\n";
                   $to = "your@email.com";
// // Mail it
                   mail($to, $subject, $message, $headers);
                 } else {
                  echo json_encode("InComplete");
                  mail($email, 'cartcheck', "Something went wrong with your order. Please contact us.");
                }
              } catch(PDOException $e) {
                $message = "Results: " . print_r( $e, true );
                mail('your@email.com', 'Paypal order error', $message);
              }


            }


          }
          ?>