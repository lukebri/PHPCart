<?php
session_start();

////// RECEIVE FROM AJAX, ADD PRODUCT ID TO ARRAY.
////// Product Ids can be duplicated in the array
if (isset($_POST)) {
    $data = $_POST;
    $id = (int)$data['prodid'];
    $_SESSION['cart'][] = $id;
}

if (isset($_SESSION['cart'])) {
    echo count($_SESSION['cart']);
}

?>