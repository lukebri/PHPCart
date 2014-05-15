<?php
require_once 'password.php';
class Users {

  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  public function userdata($id) {

    $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $query->bindValue(1, $id);

    try{
      $query->execute();
      return $query->fetch();

    } catch(PDOException $e){
      die($e->getMessage());

    }
  }

  public function updatelast($id) {
    $query = $this->db->prepare("UPDATE users
      SET last_accessed = ?
      WHERE id = ?
      ");
    date_default_timezone_set('Australia/Sydney');
    date('Y-m-d');
    $query->bindValue(1, date('Y-d-m H:i:s') );
    $query->bindValue(2, $id);

    try{

      $query->execute();

    } catch (PDOException $e){
      die($e->getMessage());
    }

  }


  public function user_exists($username) {
    $query = $this->db->prepare("SELECT COUNT(`id`) FROM users WHERE username = ?");
    $query->bindValue(1, $username);

    try{

      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1){
        return true;
      }else{
        return false;
      }

    } catch (PDOException $e){
      die($e->getMessage());
    }

  }


  public function updatePassword($password, $id) {

    $query = $this->db->prepare("UPDATE users SET password= ? WHERE id = ?");
    $query->bindValue(1, $password);
    $query->bindValue(2, $id);
    try{
      $query->execute();
    }catch(PDOException $e){
      die($e->getMessage());
    }
  }

  public function checkpassword($id, $password) {

    $password = htmlentities($_POST['currentpass']);
    $id = htmlentities($_POST['id']);

    $query = $this->db->prepare("SELECT `password`, `id` FROM `users` WHERE `id` = ?");
    $query->bindValue(1, $id);

    try{
      $query->execute();
      $data        = $query->fetch();
      $stored  = $data['password'];
      $id         = $data['id'];
    #hashing the supplied password and comparing it with the stored hashed password.
      if(password_verify($password, $stored)){
        return $id;
      }else{
        return false;
      }
    }catch(PDOException $e){
      die($e->getMessage());
    }
  }

  public function login($username, $password) {

    $password = htmlentities($_POST['password']);
    $username = htmlentities($_POST['username']);

    $query = $this->db->prepare("SELECT `password`, `id` FROM `users` WHERE `username` = ?");
    $query->bindValue(1, $username);

    try{
      $query->execute();
      $data        = $query->fetch();
      $stored  = $data['password'];
      $id         = $data['id'];
    #hashing the supplied password and comparing it with the stored hashed password.
      if(password_verify($password, $stored)){
        return $id;
      } else {
        return false;
      }
    } catch(PDOException $e){ die($e->getMessage()); }

  }


  public function email_confirmed($username) {

    $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ? AND `activated` = ?");
    $query->bindValue(1, $username);
    $query->bindValue(2, "t");

    try{

      $query->execute();
      $rows = $query->fetchColumn();

      if($rows == 1){
        return true;
      }else{
        return false;
      }

    } catch(PDOException $e){
      die($e->getMessage());
    }

  }

  public function register($username, $password, $email, $firstname, $lastname, $num){

    if(isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']) {
      try {
        include 'inc/db.php';
      } catch(PDOException $e) { echo 'ERROR: ' . $e->getMessage(); }

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

      if ($numcheck == 9) {

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
          header("Location: index.php");
          /// need to send activation email ... // needs testing
          /* mail($email, 'Please activate your account', "Hello " . $username. ",\r\nThank you for registering with us. Please visit the link below so we can activate your account:\r\n\r\nhttp://www.example.com/activate.php?email=" . $email . "&passkey=" . $passkey . "\r\n\r\n-- Example team"); */
        }
        catch(PDOException $e){die($e->getMessage());}
      }
      else {
        echo "Please input correct number.";
      }
    }
  }

}
?>