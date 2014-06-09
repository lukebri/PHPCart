<?php
session_start();
    require_once 'db.php';
    require_once 'users.class.php';
    $users      = new Users($db);
if (!empty($_POST)) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (empty($username)|| empty($password)) {
    $errors[] = 'Username and password required.';
    echo "User and password required.";
  } else if ($users->user_exists($username) === false) {
    $errors[] = "Sorry that username doesn't exist.";
  } else if ($users->email_confirmed($username) === false) {
    $errors[] = 'Sorry, but you need to activate your account.
    Please check your email.';
  } else {
    $login = $users->login($username, $password);
    if ($login === false) {
      $errors[] = 'Sorry, that username/password is invalid';
      // We echo true or false to respond to AJAX and let it know what happened :)
      echo 'false';
    }else {
      echo 'true';
      /* $login either returns false or the user's id, now we can set the session[id] */
      $_SESSION['id'] = $login;
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