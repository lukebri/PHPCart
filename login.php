<?php
session_start();
 $pageTitle = "SurfShop | Login";
 require_once 'inc/header.php';

 if ($general->is_logged_in()) {
  header("Location: index.php");
  exit;
}

$_SESSION['token'] = time() + sha1($_SERVER['REMOTE_ADDR']);

?>
<!-- End header section-->
<section id="content">
  <div class="container">
    <?php include 'inc/error.inc.php'; ?>
    <div class="mbox ajaxhide"><strong class="warning"></strong></div>
    <div class="row" id="formpage">

    <section class="col-lg-6 col-sm-6 col-xs-12" id="login">
        <p class="col_title">Login</p>
        <form action="loginsubmit.php" id="loginform" class="form-inline" method="post">
          <div class="form-group">
            <label for="emaillogin ">Email:</label>
            <input type="email" name="emaillogin" id="emaillogin" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="password">password:</label>
            <input placeholder="Password" type="password" name="password" id="password">
          </div>
          <input type="hidden" id="hp" value="">
          <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>">
          <input type="submit" id="submit" value="LOGIN"> or <a href="register.php">register here.</a>
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
