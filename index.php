<?php
session_start();
$pageTitle = "SurfShop | Home";
require_once 'inc/header.php';
 ?>
 <!-- End header section-->
 <section id="content">
   <?php
   if (isset($user) && isset($_SESSION['id'])) {
    if(!isset($_SESSION['welcomed'])) {
      $_SESSION['welcomed'] = true;
      echo '<p id="messagetop"> Wecome '  . $user{'firstname'} . ' ' . $user{'lastname'} . '</p>';
    }
  }
  ?>
  <div class="container">
    <?php include 'inc/error.inc.php'; ?>
    <div class="row" id="services">


      <?php
      $prod = $products->getprods(4);
      foreach ($prod as $product) : ?>

      <section class="col-lg-3 col-sm-3 col-xs-12">
        <p class="col_title"><?php echo $product{'name'}; ?></p>
        <p>
          <img class="prodgallery" src="<?php echo 'img/upload/' . $product{'img'}; ?>"><br>
          <?php
          $string = htmlentities($product{'description'});
    // if description > 30 chars, cut....
          if (strlen($string) > 30) {
            $stringCut = substr($string, 0, 30);
    // make sure to end after word, not a letter and append ...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')). ' ...';
          }
          echo $string;
          ?>
        </p>
        <a href="<?php echo 'product.php?id=' . $product{'id'}; ?>">
          <button class="buttonmain">$ <?php echo $product{'price'}; ?></button>
        </a>
      </section>

    <?php endforeach ?>


  </div>

</div>
<!-- /container content -->
<?php
require_once 'inc/footer.php';
?>
</body>
</html>
