<?php
session_start();
if(isset($_POST) && isset($_POST['hp']) && empty($_POST['hp'])) {
  if(
    isset($_SESSION['token']) &&
    $_SESSION['token'] == $_POST['token']
  ) {
    $data = $_POST;
    $remove = array('submit', 'reset', 'hp');
    $accepted = array('first_name', 'email', 'postcode', 'token');
    foreach($data as $field => $value) {
      if(in_array($field, $remove)) {
        unset($data[$field]);
      } elseif(!in_array($field, $accepted)) {
        echo '<p>Incorrect data received.</p>';
        die('<p><a href="form.php">Back to form</a></p>');
      }
    }
    if(count($data) != count($accepted)) {
      echo '<p>Incorrect data received.</p>';
      die('<p><a href="form.php">Back to form</a></p>');
    }
    $expressions = array(
      'first_name' => "~^[a-z]+[\-'\s]?[a-z]+$~i",
      'email' => '~^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$~',
      'postcode' => '~^\d{4}$~',
      'token' => '~^[a-z0-9]{40}$~'
    );
    foreach($data as $field => $value) {
      if(!preg_match($expressions[$field], $value)) {
        echo '<p>Invalid data received.</p>';
        die('<p><a href="form.html">Back to form</a></p>');
      }
    }
    
    mysql_connect('localhost', 'username', 'password');
    mysql_select_db('databaseName');
    $data = array_map('mysql_real_escape_string', $data);
    //Database queries can now be created and executed using $data
  }
}
?>
