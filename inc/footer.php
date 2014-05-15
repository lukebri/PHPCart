  <footer>
    <hr class="darkhr">
    <hr class="lighthr l2">
    <div class="container">
     <div class="row">

      <section class="col-lg-12 col-sm-12 col-xs-12" id="recentfooter">

        <p class="widget_header">Recently added</p>
        <?php
        $pquery = $db->query('SELECT * FROM products ORDER BY id DESC LIMIT 4');
        while($product = $pquery->fetch(PDO::FETCH_ASSOC)): ?>

        <div class="output col-lg-3">
          <p class="time"><?php echo $product{'name'}; ?></p>
          <p>
            <?php
            $string = htmlentities($product{'description'});

            if (strlen($string) > 30) {

    // if description > 30 chars, cut....
              $stringCut = substr($string, 0, 30);

    // make sure to end after word, not a letter
              $string = substr($stringCut, 0, strrpos($stringCut, ' ')). ' ...';
            }
            echo $string;
            ?>
          </p>
          <a href="<?php echo 'product.php?id=' . $product{'id'}; ?>">
            <img class="recentadd" src="<?php echo 'img/upload/' . $product{'img'}; ?>">
          </a>
        </div>
      <?php endwhile ?>

    </section>


  </div><!-- endrow -->

</div><!-- container -->
<hr class="darkhr">
<hr class="lighthr">
</footer>
<!-- /container footer -->
<div class="container copyright">
  <div class="col-lg-12"><br>

    <p class="col-lg-4 col-sm-6">Copyright <sup>&copy;</sup> <a href="http://www.luke-designs.com.au">Luke Designs 2013</a></p>
    <p class="col-lg-4 col-sm-6"> Designed by <a href="http://www.luke-designs.com.au">luke-designs.com.au</a></p>
    <p class="col-lg-4 col-sm-6">  <a href="index.php">HOME</a>  /  <a href="products.php">PRODUCTS</a>  /  <a href="contact.php">CONTACT</a></p>
  </div>
  <a href="#topdiv" id="uparrow">
    <img src="img/arrowup.png" alt="return to top">
  </a>
</div>
<!-- /container copyright -->


  <!-- Bootstrap core JavaScript
  ==================================================
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->

  <script type="text/javascript" src="js/jquery.js"></script>
  <script src="js/notify.min.js"></script>
  <script src="js/notify.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/luke.js"></script>
  <script src="js/valid.js"></script>
  <script src="js/jquery.mobile.custom.min.js"></script>