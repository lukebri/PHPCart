<?php
session_start();
$pageTitle = "SurfShop | Cart";
require_once 'inc/header.php';
if ($general->is_logged_in()) {
} else {
 $_SESSION['errors'] = "You are not logged in.";
 header("Location: index.php");
 exit;
}
$_SESSION['token'] = sha1($_SERVER['REMOTE_ADDR']) + time();

$go = false;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $go = true;
} else {
    echo "<p id=\"messagetop\">Nothing added to cart</p>";
}
?>
<!-- End header section-->

<?php if ($go): ?>
 <section id="content">
     <div class="container">
      <?php include 'inc/error.inc.php'; ?>
      <section id="viewcart">
        <?php foreach ($_SESSION['cart'] as $prod): ?>
            <?php
            // Get total price of each cart item stored in session
            $price = $general->getPrice($prod);
            $total[] = $price{0};
             ?>
        <?php endforeach ?>
        <?php // Set total in to session for cart submission later
            $_SESSION['total'] = array_sum($total); ?>

        <aside class="col-lg-6 col-sm-6 col-xs-6" id="checkout">
        <button class="userid" value="<?=$_SESSION['user']{'id'};?>"></button>
            <p><b>Cart Total</b><b id="cartcount">(<?php echo count($total); ?>)</b>: $<strong id="total"><?php echo array_sum($total); ?></strong></p>
            <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_self" id="paypalcheckout">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="business" value="seller@test123.com" />
                <input type="hidden" name="item_name" value="Hakoona Purchase" />
                <input type="hidden" name="currency_code" value="AUD" />
                <input type='hidden' name='rm' value='2'>
                <input type="hidden" id="invoice" name="invoice" value="">
                <input type="hidden" class="amount" name="amount" value="<?=array_sum($total);?>" />
                <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_paynowCC_LG.gif" name="submit" alt="PayPal" />
            </form>
        </aside>

        <aside class="col-lg-6 col-sm-6 col-xs-6">
            <?php $icount = array_count_values($_SESSION['cart']);
            foreach ($_SESSION['cart'] as $prod): ?>
            <?php $item = $products->getprod($prod); ?>
            <?php
            $id = $item{0}{'id'}; // Skips if item already shown in cart
                if (isset($skip)) {
                   if (in_array($id, $skip)) {
                    continue;
                }
            }
            ?>
        <p class="prodcart">
            <a href="#" value="<?=$item{0}{'id'}; ?>" class="remcart">X</a>
            <b class="pprice" value="<?=$item{0}{'price'};?>">$<?=$item{0}{'price'};?></b>
            <b class="quant"><?=$icount[$id]; ?></b>
            <b><a href="product.php?id=<?=$item{0}{'id'}; ?>"><?=$item{0}{'name'}; ?></a></b>
            <img src="img/upload/<?=$item{0}{'img'};?>" alt="cart product">
        </p>
        <?php if($icount[$id] > 1) {
            $skip[] = $id;
        } ?>
    <?php endforeach ?>
    <?php $skip = null; ?>
</aside>

</section>

</div>
<?php endif ?>
<!-- /container content -->
<?php
require_once 'inc/footer.php';
?>
</body>
</html>
