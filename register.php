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
              <input type="text" name="username" placeholder="user Name" id="username" pattern=".{5,}" required>
            </p><p>
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" placeholder="First Name" id="firstname" pattern=".{2,}" required>
          </p><p>
          <label for="lastname">Surname:</label>
          <input type="text" name="lastname" placeholder="Last Name" id="lastname" pattern=".{2,}" required>
        </p><p>
      </div>
      <div class="col-lg-6 col-sm-6 col-xs-12">
        <p>
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" placeholder="Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required>
        </p><p>
        <label for="password">password:</label>
        <input placeholder="Password" type="password" name="password" id="pass_word" pattern=".{5,}" required>
      </p><p>

      <label for="num">3+6 = ?</label><input name="num" type="text" id="num" required>
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
