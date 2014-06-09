<?php
session_start();

if (!isset($_GET['passkey']) ) {
  $errors[] = "This activation URL is not valid.";
  $_SESSION['return'] = $errors;
  header('Location: index.php');
} else {

  try {
    require 'inc/db.php';
    $passkey = trim($_GET['passkey']);
    $email = trim($_GET['email']);
    $passkey = preg_replace('/[^A-Za-z0-9\. -]/', '', $passkey);
    $query = $db->prepare("SELECT COUNT(`id`) FROM users WHERE passkey = ? AND email = ?");
    $query->bindValue(1, $passkey);
    $query->bindValue(2, $email);

    try{
      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1){
/// update db
        $stmt = $db->prepare('UPDATE users SET activated = "t", passkey = "active" WHERE passkey = :passkey AND activated = "f" AND email = :email');
        $stmt->execute(array(
          ':passkey'   => $passkey,
          ':email'   => $email
          ));
        $errors[] = "Account activated! Please login.";
        $_SESSION['return'] = $errors;
        header('Location: index.php');

      }else{
        $errors[] = "Account already active or does not exist";
        $_SESSION['return'] = $errors;
        header('Location: index.php');
      }

    } catch (PDOException $e){
      die($e->getMessage());
    }

  } catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $_SESION['return'] = $e->getMessage();
  }

}