<?php
session_start();

////// RECEIVE FROM AJAX, ADD REMOVE MATCHING IDs FROM ARRAY.

if (isset($_POST)) {
    /// If receives Delete All Command from Ajax
    if (isset($_POST['DelAll'])) {
        unset($_SESSION['cart']);
        unset($_SESSION['total']);
        exit;
    }

    $data = $_SESSION['cart'];
    $id = (int)$_POST['Del'];

    $key = array_search($id, $data);
    if(($key = array_search($id, $data)) !== false) {
        unset($data[$key]);
    }
    $_SESSION['cart'] = $data;

    //Remove item's price from cart total stored in session
    if (isset($_SESSION['total'])) {
        $total = $_SESSION['total'];
        $reduce = $_POST['price'];
        $total = $total - $reduce;
        $_SESSION['total'] = $total;
    }


}