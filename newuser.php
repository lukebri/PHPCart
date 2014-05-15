<?php
session_start();
 $pageTitle = "SurfShop | Register";
 require_once '../inc/header.php';

 if(isset($_POST) && isset($_POST['hp']) && empty($_POST['hp'])) {
  if(isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']) {
    $data = $_POST;
    echo "set data";
  }
  unset($_SESSION['token']);
echo "done success";
} else {
  unset($_SESSION['token']);
  session_destroy();
  echo "not set";
}

?>
<!-- End header section-->
<section id="content">
  <div class="container">
    <div class="row" id="formpage">

      <section class="col-lg-12 col-sm-12 col-xs-12" id="login">
        <p class="col_title">Result</p>

      </section>

    </div>

  </div>
  <!-- /container content -->
  <?php
  require_once '../inc/footer.php';
  ?>
</body>
</html>
