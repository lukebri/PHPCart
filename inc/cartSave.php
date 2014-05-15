<?php session_start();

if (isset($_POST) && $_POST['type'] == "paypal") {

    require_once 'paypal.class.php';
    $paypal    = new Paypal($db);
    $data = $_POST;
    $paypal->newOrder($data);
       // echo json_encode($orderID);
// mail('test@email.com', 'cartcheck', "test run");

}



?>