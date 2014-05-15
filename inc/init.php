<?php
require_once 'password.php';
if(isset($_SESSION)) {
    require_once 'inc/db.php';
    require_once 'users.class.php';
    require_once 'general.class.php';
    require_once 'products.class.php';
    require_once 'functions.php';
    $users      = new Users($db);
    $general    = new General($db);
    $products    = new Products($db);
    $errors     = array();

    if ($general->is_logged_in()) {

      $inactive = 900;

      if(isset($_SESSION['timeout']) ) {
          $session_life = time() - $_SESSION['timeout'];
          if($session_life > $inactive) {
            header("Location: logout.php?error=timeout");
        }
    }
}
    //// On page load, set timeout to current time
    // After session life is checked for expiry
$_SESSION['timeout'] = time();

}
    // Grab user info when loggedin (eg Name, Username, Email)
if (isset($_SESSION['id'])) {
    $user = $users->userdata($_SESSION['id']);
    $_SESSION['user'] = $user;
}
if (isset($_GET['id']) && $page = "prod") {
    $product = $products->getprod($_GET['id']);
}
if (isset($_GET['pg']) && $page = "products" && $_GET['pg'] < 0) {
    header('Location: products.php?pg=1');
}

?>