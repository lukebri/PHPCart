<?php
session_start();
    require_once 'db.php';
    require_once 'users.class.php';
    $users      = new Users($db);
if (empty($_POST) === false ) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (empty($username) === true || empty($password) === true) {
    $errors[] = 'Username and password required.';
    echo "User and pw required.";
  } else if ($users->user_exists($username) === false) {
    $errors[] = 'Sorry that username doesn\'t exist.';
  } else if ($users->email_confirmed($username) === false) {
    $errors[] = 'Sorry, but you need to activate your account.
    Please check your email.';
  } else {
    $login = $users->login($username, $password);
    if ($login === false) {
      $errors[] = 'Sorry, that username/password is invalid';
      echo 'false';
    }else {
      echo 'true';
      // username/password is correct and the login method of the $users object returns the user's id, which is stored in $login.
      $_SESSION['id'] =  $login; // The user's id is now set into the user's session  in the form of $_SESSION['id']
      $users->updatelast($login);

      #Redirect to index, unset post and token to prevent resubmission
      unset($_POST);
      unset($_SESSION['token']);
      // header breaks ajax request, so its disabled
      // header('Location: index.php');
    }
  }
}
?>