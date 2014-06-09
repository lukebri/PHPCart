<?php session_start();

if (isset($_POST) && $_POST['type'] == "paypal") {
    require_once 'paypal.class.php';
    $paypal    = new Paypal($db);
    $data = $_POST;
    $invoice = $paypal->newOrder($data);
    /* newOrder function returns the orderID stored for this order
    AJAX Success needs this return value for the paypal submission invoice field
     */
    echo json_encode($invoice);
}