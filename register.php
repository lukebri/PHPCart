<?php
session_start();
$pageTitle = "SurfShop | Register";
require_once 'inc/header.php';

if ($general->is_logged_in()) {
  header("Location: index.php");
  exit;
}

if (!isset ($_SESSION['token'])) {
 $_SESSION['token'] = sha1($_SERVER['REMOTE_ADDR']) + time();
}
unset($_POST);

?>
<!-- End header section-->
<section id="content">
<h2 class="hide">Register your account</h2>
  <div class="container">
    <div id="formpage">

      <section class="col-lg-7 col-sm-7 col-xs-12" id="login">
        <?php include 'inc/error.inc.php'; ?>
        <div class="formheader">
          <p class="col_title">Register</p><p class="col_title errorform"></p>
        </div>
        <form action="register2.php" method="post" id="regform">
          <div class="col-lg-6 col-sm-6 col-xs-12" >
            <p>
              <label for="user_name">Username:</label>
              <input type="text" title="Min 5 chars" name="username" placeholder="user Name" id="username">
            </p><p>
            <label for="firstname">First name:</label>
            <input type="text" title="Min 2 chars" name="firstname" placeholder="First Name" id="firstname">
          </p><p>
          <label for="lastname">Surname:</label>
          <input type="text" title="Min 2 chars" name="lastname" placeholder="Last Name" id="lastname">
        </p><p>
      </div>
      <div class="col-lg-6 col-sm-6 col-xs-12">
        <p>
          <label for="email">Email:</label>
          <input type="email" title="Email not valid" name="email" id="email" placeholder="Email">
        </p><p>
        <label for="password">Password:</label>
        <input placeholder="Password" title="Min 5 chars" type="password" name="password" id="pass_word">
      </p><p>

      <label for="num">3+6 = ?</label><input name="num" type="text" id="num">
    </p>
  </div>

  <input type="hidden" name="hp" value="" id="hp">
  <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>">
  <p><input type="submit" id="register"></p>

</form>
</section>

</div>

</div>
<!-- /container content -->
<?php
require_once 'inc/footer.php';
?>
</body>
</html>
