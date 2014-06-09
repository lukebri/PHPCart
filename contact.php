<?php
session_start();
$pageTitle = "SurfShop | Contact";
require_once 'inc/header.php';

if (isset($_POST) && !empty($_POST)) {
  /// If user resubmits form, the page is refreshed via javascript to clear POST.
 if (isset($_SESSION['reloadform']) && $_SESSION['reloadform'] === true) {
  $_SESSION['reloadform'] = false;
  echo '<script type="text/javascript">
  location.reload();
</script>';
exit;
}
if (!empty($_POST['hp']) || empty($_SESSION['token'])) {
  header('Location: index.php');
  exit;
}
if ($_SESSION['token'] != $_POST['token']) {
  echo $_SESSION['token'] . '<br>';
  echo "token mismatch" . '<br>';
  header('Location: index.php');
} else {
  $message = "";
  foreach ($_POST as $key => $value)   {
    if ($key == 'hp' || $key == 'token') {continue;}
    $message .= htmlentities($key) . ":";
    $message .= htmlentities($value) . "\r\n";
  }
  mail(adminEmail, "Contact Form", $message);
  unset($_POST);
  unset($_SESSION['token']);
  $sent = true;
  if (!$sent) {
    $_SESSION['reloadform'] = true;
  }

}
} else {
  $_SESSION['token'] = sha1($_SERVER['REMOTE_ADDR']) + time();
}




?>
<section id="content">
  <h2 class="hide">Contact</h2>
  <div class="container">
    <div class="row" id="contact">

      <section class="col-lg-6 col-sm-6 col-xs-12">
        <div id="sent">Thank you, I'll be in contact as soon as I can!</div>
        <p class="col_title">CONTACT ME</p>
        <?php if (!$sent): ?>

          <form role="form" method="post" action="contact.php">
            <input type="hidden" value="" name="hp">
            <input type="hidden" value="<?php echo $_SESSION['token']; ?>" name="token">
            <div class="form-group">
              <input type="text" name="first_name" class="form-control" placeholder="Enter name *" required/>
            </div>

            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Enter email *" required/>
            </div>

            <div class="form-group dropdown">
             <label for="reason" class="reason">What's this about?</label>
             <select name="reason" id="reason">
              <option value="Other">Not listed</option>
              <option value="A product question">A product question</option>
              <option value="An order">Orders</option>
              <option value="Account question">Accounts</option>
            </select>
          </div>

          <div class="form-group">
            <textarea rows="3" placeholder="Message *" name="message" required/></textarea>
          </div>

          <button type="submit" class="btn btn-default">SEND MAIL</button>
        </form>
      <?php else: ?>
        Yay sent
      <?php endif; ?>
    </section>

  </div> <!-- /row contact -->

</div>  <!-- /container content -->

<?php
require_once 'inc/footer.php';
?>
</body>
</html>
