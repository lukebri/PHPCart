<?php
session_start();

////// RECEIVE FROM AJAX, ADD REMOVE MATCHING IDs FROM ARRAY.

if (isset($_POST)) {
    /// If receives Delete All Command from Ajax
    if (isset($_POST['DelAll'])) {
        unset($_SESSION['cart']);
        exit;
    }

    $data = $_SESSION['cart'];
    $id = (int)$_POST['Del'];

    $key = array_search($id, $data);
    if(($key = array_search($id, $data)) !== false) {
        unset($data[$key]);
    }
    $_SESSION['cart'] = $data;

}


?>