<?php session_start();
require_once 'inc/password.php';
require_once 'inc/db.php';
require_once 'inc/users.class.php';
require_once 'inc/general.class.php';
$users      = new Users($db);
$general = new General($db);

if ($general->is_logged_in()) {
  echo "You can't make a new account logged in.";
  exit;
}

if(!isset($_POST['hp']) || !isset($_POST['token']) ) {
// $errors[] = 'HP or Token missing. Please try again.';
  echo "You've already submitted this form. Please refresh if this was in error.";
  exit;
} else {
  $numcheck = (int)strip_tags($_POST['num']);

  if ($users->emailExists($_POST['email'])) {
    echo "That email address is in use.";
    exit;
  }

  if ($numcheck !== 9) {
    echo "You got the number check wrong.";
    exit;
  } else {

    if(isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token'])
    {

      if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "The email entered is not valid.";
        exit;
      }

      if (empty ($_POST['password'])) {
        echo "Please enter a password.";
        exit();
      }


      foreach($_POST as $v => $key) {
        $result = "";
        $cancel = false;
    if(empty($key)) { // Check for empty post variables besides token and hp, we don't need these once as we check earlier
    if ($v == "hp" || $v == "token") {continue;} else {
      $result .= "Please enter: $v" . '<br>';
      echo $result;
      exit;
      $cancel = true;
    }
  }
}

  /// Set values

$username = htmlentities($_POST['username']);
$firstname = htmlentities($_POST['firstname']);
$lastname = htmlentities($_POST['lastname']);
$email = htmlentities($_POST['email']);
$passkey = sha1($username + time());
$password = htmlentities($_POST['password']);
$password = password_hash($password, PASSWORD_DEFAULT);
date_default_timezone_set('Australia/Sydney');
$registered = date('Y-m-d');
$numcheck = htmlentities($_POST['num']);

  /// Insert user

$query = $db->prepare("INSERT INTO users (`password`, `email`, `passkey`, `username`, `lastname`, `firstname`, `registered`) VALUES (?, ?, ?, ?, ?, ?, ?) ");

$query->bindValue(1, $password);
$query->bindValue(2, $email);
$query->bindValue(3, $passkey);
$query->bindValue(4, $username);
$query->bindValue(5, $lastname);
$query->bindValue(6, $firstname);
$query->bindValue(7, $registered);

try{
  $query->execute();
  unset($_SESSION['token']);
  mail($email, 'Please activate your account', "Hello " . $username. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://lbriedis.richmondit.net.au/diploma/0-mycart/activate.php?email=" . $email . "&passkey=" . $passkey . "\r\n\r\n-- Hakoona");
  echo "true";
}
catch(PDOException $e){
  die($e->getMessage());
}

}
}
}


?>