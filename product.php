<?php
session_start();
 $pageTitle = "SurfShop | Products";
 $page = "prod";
 require_once 'inc/header.php';
 if (!isset($_GET['id'])) {
  header("Location: product.php?id=1");
 }
?>
 <section id="content">
  <div class="container">
    <div class="row" id="product">

     <section class="col-lg-12 col-sm-12 col-xs-12">
       <div class="prodleft col-lg-6 col-sm-6 col-xs-12">
        <p class="col_title"><?php if ($product{0}{'name'} == true) {
          echo $product[0]['name'];
        } ?></p>
        <p>
          <img class="prodgallery" src="<?php if ($product{0}{'img'} == true) {
          echo 'img/upload/' . $product[0]['img'];
        } ?>"><br>
        </p>
          <button class="buttonmain add" value="<?php echo $_GET['id']; ?>">Add to Cart</button>
      </div>

      <div class="prodright col-lg-6 col-sm-6 col-xs-12">
        <p>Stock: <?php if ($product{0}{'stock'} == true) {
          echo "in stock";
        } else "no stock"; ?></p>
        <p class="price">AUD: $ <?php echo $product{0}{'price'}; ?></p>
         <p class="desc"><?php echo $product{0}{'description'}; ?></p>
      </div>
    </section>

  </div>

</div>
<!-- /container content -->
<?php
require_once 'inc/footer.php';
?>
</body>
</html>