<?php
ob_start();
require_once 'inc/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Luke Designs Template">
  <meta name="author" content="Luke Designs">
  <title><?php echo $pageTitle; ?></title>
<link rel="icon" type="image/ico" href="http://www.lukedev.net.au/0-mycart/favicon.ico">
  <!-- Bootstrap + Theme CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/theme.css" rel="stylesheet">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="/js/html5shiv.js"></script>
  <script src="/js/respond.min.js"></script>
  <![endif]-->
</head>
<body>

  <div id="topbutton">
   <?php if ($general->is_admin()) { ?>
   <a href="admin.php"><button>ADMIN</button></a>
   <?php } ?>
   <?php if ($general->is_logged_in()) { ?>
   <a href="logout.php"><button>LOGOUT</button></a>
   <?php } else { ?>
   <div class="mbox"><strong class="warning"></strong></div>
   <form action="" id="loginform" class="form-inline" method="post">
    <div class="form-group">
      <label class="sr-only" for="lusername">Username:</label>
      <input type="text" class="login" min="5" name="lusername" id="lusername" placeholder="Username" required>
    </div>
    <div class="form-group">
      <label class="sr-only" for="lpassword">password:</label>
      <input class="login" placeholder="Password" type="password" name="lpassword" id="lpassword" required>
    </div>
    <input type="hidden" id="hp" value="">
    <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>">
    <input type="submit" id="submit" value="LOGIN">
  </form>
</a>
<a href="register.php"><button>REGISTER</button></a>
<?php } ?>
</div>
<header>
  <hr class="darkhr">
  <hr class="lighthr l2">
  <div class="container">
    <div class="row col-lg-12" id="topdiv">
     <section id="logo" class="col-lg-4 col-sm-4 col-xs-12">
      <div id="logotext">
        <a href="./">
          <img src="img/lb-logo.png" alt="logo">
        </a>
      </div>
      <!--  <img src="img/light.png" class="light" alt="Light"> -->
    </section><!-- /logo -->

    <section id="navig" class="col-lg-8 col-sm-8 col-xs-12">
      <div id="cart">
        <a href="cart.php">There are <?php if (isset ($_SESSION['cart'])) {echo '<b>' . count($_SESSION['cart']) . '</b>'; } else { echo '<b>' . "no" . '</b>'; } ?> items in your cart.</a>
        <?php if (isset($_SESSION['carttotal'])) { echo '<br>' . "Total:" . $_SESSION['carttotal']; } ?>
        </div>
        <nav>
         <ul>
           <li><a href="index.php">Home</a></li>
           <li><a href="products.php">Products</a></li>
           <li><a href="contact.php">Contact</a></li>
         </nav>
       </section><!-- /nav menu -->

     </div><!-- /logo/menu row -->

   </div><!-- end container / header -->
 </header>
 <!-- End header section-->
