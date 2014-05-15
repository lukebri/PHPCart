<?php
session_start();

/////// GET ADD TO CART TESTING WITH MANUAL VARIABLES
// if (isset($_GET)) {
//     $id = (int)$_GET['id'];
//     // $_SESSION['added'][] = intval($id);
//     echo '<pre>';
//     var_dump($id);
// }

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