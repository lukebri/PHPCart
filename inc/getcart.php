<?php
session_start();
if (isset($_SESSION['cart'])) {
    echo json_encode($_SESSION['cart']);
}
 ?>