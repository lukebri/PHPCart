<?php
if (isset($_GET['id']) && is_numeric($_GET['id']) ) {
  $id = htmlentities($_GET['id']);
  $prod = $products->getprod($id);
}
?>
<div id="editprod" class="col-lg-5 col-sm-6 col-xs-12 adminbox">
  <h2>Edit Product</h2>
  <form class="form-horizontal editform"role="form" method="post"  enctype="multipart/form-data">
    <input type="hidden" name="type" value="editproduct">
    <div class="form-group">
     <label class="col-sm-4 control-label" for="username">Product Name:</label>
     <div class="col-sm-8">

       <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"name"};} ?>" class="form-control" id="name" name="name" type="text" required>
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-4 control-label" for="firstname">Price:</label>
     <div class="col-sm-8">
       <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"price"};} ?>" class="form-control" id="price" name="price" type="text" required>
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-4 control-label" for="surname">Description:</label>
     <div class="col-sm-8">
       <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"description"};} ?>" class="form-control"  name="description" type="text" required>
     </div>
   </div>

   <div class="form-group">
   <label class="col-sm-4 control-label" for="surname">Stock:</label>
     <div class="col-sm-8">
     <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"stock"};} ?>" class="form-control" id="sku" name="stock" type="text" required>
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-4 control-label" for="surname">SKU:</label>
     <div class="col-sm-8">
       <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"sku"};} ?>" class="form-control" id="sku" name="sku" type="text" required>
     </div>
   </div>

   <div class="form-group">
    <label for="upload" class="col-sm-4 control-label">Product Image</label>
    <div class="col-sm-8">
      <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> value="<?php if(isset($_GET['id'])) {echo $prod{0}{"id"};} ?>" type="file" name="imgupload" id="upload" class="upload">
      <p class="help-block">Upload product image.</p>
    </div>
  </div>

  <?php if(isset($_GET['id'])) : { ?>
   <div class="form-group">
    <div class="col-sm-8" style="text-align: right;">
      <input type="hidden" name="curimg" value="<?=$prod{0}{"img"};?>">
      <img class="currentimg" src="img/upload/<?php echo $prod{0}{"img"}; ?>" alt="current image">
      <p class="help-block">Current image.</p>
    </div>
  </div>
  <?php } endif; ?>

  <input <?php if(!isset($_GET['id'])) {echo 'disabled';}?> type="hidden" name="id" value="<?php if(isset($_GET['id'])) {echo $prod{0}{'id'};} ?>">
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button <?php if(!isset($_GET['id'])) {echo 'disabled';}?>  type="submit" class="btn btn-default">Save Product</button>
    </div>
  </div>
</form>
</div>