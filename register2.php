<?php
session_start();
require_once 'password.php';
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
 echo "hp or token missing";
 exit;
} else {
  $numcheck = (int)strip_tags($_POST['num']);

  if ($numcheck !== 9) {
    echo "numcheck wrong";
    // $_SESSION['errors'] = 'Number check was invalid.';
    exit;
  } else {

    if(isset($_SESSION['token'])) //&& $_SESSION['token'] == $_POST['token'])
{

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "Email not valid bro";
    exit;
  }

  if (empty ($_POST['password'])) {
    echo "Password not entered.";
    exit();
  }


  // foreach($_POST as $v => $key) {
  //   if ($v == "hp") {echo $v . '222';exit;}
  // }

  foreach($_POST as $v => $key) {
    $result = "";
    $cancel = false;
    if(empty($key)) {
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
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  date_default_timezone_set('Australia/Sydney');
  $registered = date('Y-m-d');
  $numcheck = htmlentities($_POST['num']);

      /// Verification


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
    $_SESSION['register_ok'] = $passkey;
    unset($_SESSION['token']);
    echo "true";
        // header("Location: index.php");
//         /* mail($email, 'Please activate your account', "Hello " . $username. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://www.example.com/activate.php?email=" . $email . "&passkey=" . $passkey . "\r\n\r\n-- Example team"); */
  }
  catch(PDOException $e){die($e->getMessage());}
  //   header("Location: index.php");
  //   exit;
}
}
}


// NOTSURE// if(!isset($_POST) && !isset($_POST['hp']) && !isset($_POST['token']) && !empty($_POST['hp']))

// if(!isset($_POST) || !isset($_POST['hp']) || !isset($_POST['token']) && !empty($_POST['hp'])) {
//   $errors[] = 'All fields are required.';
//   header("Location: register.php");
//   exit;
// } else {
//   $numcheck = (int)strip_tags($_POST['num']);
//   if ($numcheck !== 9) {
//     $_SESSION['errors'] = 'Number check did not pass.';
//     header("Location: register.php");
//     exit;
//   } else {
//     if(isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']) {
//       try {
//         include 'inc/db.php';
//       } catch(PDOException $e) { echo 'ERROR: ' . $e->getMessage(); }

//       /// Set values

//       $username = htmlentities($_POST['username']);
//       $firstname = htmlentities($_POST['firstname']);
//       $lastname = htmlentities($_POST['lastname']);
//       $email = htmlentities($_POST['email']);
//       $passkey = sha1($username + time());
//       $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
//       date_default_timezone_set('Australia/Sydney');
//       $registered = date('Y-m-d');
//       $numcheck = htmlentities($_POST['num']);

//       /// Verification


//       $query = $db->prepare("INSERT INTO users (`password`, `email`, `passkey`, `username`, `lastname`, `firstname`, `registered`) VALUES (?, ?, ?, ?, ?, ?, ?) ");

//       $query->bindValue(1, $password);
//       $query->bindValue(2, $email);
//       $query->bindValue(3, $passkey);
//       $query->bindValue(4, $username);
//       $query->bindValue(5, $lastname);
//       $query->bindValue(6, $firstname);
//       $query->bindValue(7, $registered);

//       try{
//         $query->execute();
//         $_SESSION['register_ok'] = $passkey;
//         unset($_SESSION['token']);
//         header("Location: index.php");
// //         /* mail($email, 'Please activate your account', "Hello " . $username. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://www.example.com/activate.php?email=" . $email . "&passkey=" . $passkey . "\r\n\r\n-- Example team"); */
//       }
//       catch(PDOException $e){die($e->getMessage());}
//   //   header("Location: index.php");
//   //   exit;
//     }
//   }
// }







// if (isset($_POST['submit'])) {

//   if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])){

//     $errors[] = 'All fields are required.';

//   }else{

//         #validating user's input with functions that we will create next
//         if ($users->user_exists($_POST['username']) === true) {
//             $errors[] = 'That username already exists';
//         }
//         if(!ctype_alnum($_POST['username'])){
//             $errors[] = 'Please enter a username with only alphabets and numbers';
//         }
//         if (strlen($_POST['password']) <6){
//             $errors[] = 'Your password must be at least 6 characters';
//         } else if (strlen($_POST['password']) >24){
//             $errors[] = 'Your password cannot be more than 24 characters long';
//         }
//         if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
//             $errors[] = 'Please enter a valid email address';
//         }else if ($users->email_exists($_POST['email']) === true) {
//             $errors[] = 'That email already exists.';
//         }
//   }

//   if(empty($errors) === true){

//     $username   = htmlentities($_POST['username']);
//     $password   = $_POST['password'];
//     $firstname   = htmlentities($_POST['firstname']);
//     $lastname   = htmlentities($_POST['lastname']);
//     $email    = htmlentities($_POST['email']);

//     $users->register($username, $password, $email, $firstname, $lastname);
//     header('Location: register.php?success');
//     exit();
//   }
// }

// if (isset($_GET['success']) && empty($_GET['success'])) {
//   echo 'Thank you for registering. Please check your email.';
// }





?>