<?php
session_start();
$pageTitle = "SurfShop | Home";
require_once 'inc/header.php';
require_once 'inc/admin.class.php';
$admin      = new Admin($db);
if ($general->is_logged_in()) {
} else {
  header("Location: index.php");
  exit;
}
?>
<!-- End header section-->
<?php include 'inc/error.inc.php'; ?>
<?php
// echo '<pre>';
// echo var_dump($data);
// echo '</pre>';
if (isset($_GET['delete']) && is_numeric($_GET['delete']) ) {
  $admin->deleteProduct();
}


if (isset($_POST) && !empty($_POST) && $_POST['type'] == "edituser") {
  $admin->editUser();
}

if (isset($_POST) && !empty($_POST) && $_POST['type'] && $general->is_admin() == "addproduct") {
  $admin->addProduct();
}

?>

<section id="content" class="admin">

  <div class="container">
    <br><br>
    <?php
    if (isset($_GET['page']) && $_GET['page'] == "edit"): ?>
    <?php $admin->editProduct();
    include 'inc/edit.inc.php'; ?>
  <?php endif; ?>

  <?php
  if (isset($_GET['page']) && $_GET['page'] == "orders"): ?>
  <?php
  $orders = $admin->showOrders($_SESSION['id']);
  include 'inc/orders.inc.php';
  ?>

<?php endif; ?>

<?php
if (isset($_GET['page']) && $_GET['page'] == "edituser"): ?>
<?php include 'inc/edituser.inc.php'; ?>
<?php endif; ?>

<?php
if (isset($_GET['page']) && $_GET['page'] == "addproduct"): ?>
<?php include 'inc/addproduct.inc.php'; ?>
<?php endif; ?>

<div id="adminmenu" class="col-lg-6 col-sm-6 col-xs-12">
  <ul id="amenu">
    <a href="?page=edituser" value="edituser"><li>Edit User Account</li></a>
    <a href="?page=orders" value="edituser"><li>My Orders</li></a>
    <?php if ($general->is_admin()): ?>
      <a href="?page=addproduct" value="addprod"><li>Add Product</li></a>
      <a href="?page=edit" value="editprod"><li>Edit Products</li></a>
    <?php endif ?>
  </ul>
  <div class="admindata">
    <?php
    if (isset($_GET['page']) && $_GET['page'] == "edit"): ?>
    <?php
    $pquery = $db->query('SELECT * FROM products ORDER BY id DESC');
    ?>
    <ul>
      <?php while($product = $pquery->fetch(PDO::FETCH_ASSOC)): ?>
        <li class="productadmin">
          <div class="nameblock">
            <a class="editc" href="?page=edit&id=<?php echo $product['id']; ?>"><?php echo $product['name'];?></a><br>
            <a href="admin.php?delete=<?php echo $product['id']; ?>">DELETE</a>
          </div>
          <img src="img/upload/<?php echo $product['img']; ?>" alt="thumbnail" class="athumb">
          <blockquote>
            <?php           $string = htmlentities($product{'description'});

            if (strlen($string) > 35) {

// if description > 35 chars, cut....
              $stringCut = substr($string, 0, 35);

// make sure to end after word, not a letter
              $string = substr($stringCut, 0, strrpos($stringCut, ' ')). ' ...';
            }
            echo $string; ?>
          </blockquote>
        </li>
      <?php endwhile ?>
    </ul>
  <?php elseif($general->is_admin()): ?>
    <?php
    $pquery = $db->query('SELECT * FROM products ORDER BY id DESC LIMIT 3');
    ?>
    <ul>
      <h3>Recently Added:</h3>
      <?php while($product = $pquery->fetch(PDO::FETCH_ASSOC)): ?>
        <li class="productadmin">
          <div class="nameblock">
            <a class="editc" href="?page=edit&id=<?php echo $product['id']; ?>"><?php echo $product['name'];?></a><br>
            <a href="admin.php?delete=<?php echo $product['id']; ?>">DELETE</a>
          </div>
          <img src="img/upload/<?php echo $product['img']; ?>" alt="thumbnail" class="athumb">
          <blockquote>
            <?php $string = htmlentities($product{'description'});
            if (strlen($string) > 35) {
              $stringCut = substr($string, 0, 35);
              $string = substr($stringCut, 0, strrpos($stringCut, ' ')). ' ...';
            }
            echo $string; ?>
          </blockquote>
        </li>
      <?php endwhile ?>
    </ul>
  <?php endif; ?>
</div>
</div>

</div>
<br>
</div>

</div>
<!-- /container content -->
<?php
require_once 'inc/footer.php';
?>
</body>
</html>
