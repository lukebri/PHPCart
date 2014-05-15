<?php
session_start();

if (isset($_GET['error']) && $_GET['error'] == "timeout" ) {
    session_destroy();
    session_start();
 $_SESSION['errors'] = "You were logged out due to inactivity.";
 header('Location:index.php');
 exit;
}

session_destroy();
header('Location:index.php');
?>