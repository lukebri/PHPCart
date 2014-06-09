<?php
session_start();
if (isset($_SESSION['cart']) && $_POST['type'] == "getCartSession") {
    echo json_encode($_SESSION['cart']);
}

if (isset($_SESSION['cart']) && $_POST['type'] == "getTotal") {
    echo json_encode($_SESSION['total']);
}
?>