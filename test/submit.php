<?php
// Email Submit
// Note: filter_var() requires PHP >= 5.2.0
if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['text']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {

  // detect & prevent header injections
  $test = "/(content-type|bcc:|cc:|to:)/i";
  foreach ( $_POST as $key => $val ) {
    if ( preg_match( $test, $val ) ) {
      exit;
    }
  }

  //send email
  $result = '.$_POST['name'] . $_POST['text'] . $_POST['email']';
  return $result;
}
?>
