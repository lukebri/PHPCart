<?php
session_start();
$pageTitle = "SurfShop | Products";
require_once 'inc/header.php';
$page = "products";
// retrieve current page number from query string; set to 1 if blank
if (empty($_GET["pg"]) || $_GET["pg"] == 0) {
  $current_page = 1;
} else {
  $current_page = $_GET["pg"];
}

if ($_GET["pg"] == 0) {
  header("Location: products.php?pg=1");
}
// set strings like "frog" to 0; remove decimals
$current_page = intval($current_page);

// redirect too-small page numbers (or strings converted to 0) to the first page
if ($current_page < 1) {
  header("Location: products.php?pg=1");
}



$total_products = $products->get_products_count();
$products_per_page = 8;
$total_pages = ceil($total_products / $products_per_page);

// redirect too-large page numbers to the last page
if ($current_page > $total_pages) {
  header("Location: products.php/?pg=" . $total_pages);
}



// determine the start and end shirt for the current page; for example, on
// page 3 with 8 shirts per page, $start and $end would be 17 and 24
$start = (($current_page - 1) * $products_per_page) + 0;
$end = $current_page * $products_per_page;
if ($end > $total_products) {
  $end = $total_products;
}

$products = $products->get_products_subset($start,$end);
?>


<div class="pagination">
  <!--<a href="#" class="page gradient">first</a> -->
  <?php for ($i=1; $i <= $total_pages; $i++) : ?>
    <a href="products.php?pg=<?php echo $i; ?>" class=
      "page gradient <?php if($_GET['pg'] == $i) {
        echo "active";
      } ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
<!--<a href="#" class=
  "page gradient">last</a> -->
</div>

<section id="content">
  <h2 class="hide">View our products</h2>
  <div class="container">
    <?php include 'inc/error.inc.php'; ?>
    <div class="row" id="services">

      <?php
// $products = $products->getprods(4);
      foreach($products as $product) {
        include("inc/productloop.php");
      }
      ?>

    </div>

  </div>
  <!-- /container content -->
  <?php
  require_once 'inc/footer.php';
  ?>
</body>
</html>


