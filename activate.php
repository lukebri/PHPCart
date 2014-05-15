<?php
session_start();
if (!isset($_GET['code']) ) {
    $errors[] = "Account already active or does not exist.";
    $_SESSION['return'] = $errors;
    header('Location: index.php');
} else {

    try {
        require 'inc/db.php';
        $code = trim($_GET['code']);
        $code = preg_replace('/[^A-Za-z0-9\. -]/', '', $code);

        $query = $db->prepare("SELECT COUNT(`id`) FROM users WHERE passkey = ?");
        $query->bindValue(1, $code);

        try{

          $query->execute();
          $rows = $query->fetchColumn();

          if($rows == 1){
        /// update db
            $stmt = $db->prepare('UPDATE users SET activated = "t", passkey = :passkey WHERE passkey = :code AND activated = "f"');
            $stmt->execute(array(
                ':code'   => $code,
                ':passkey'   => "active"
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


?>