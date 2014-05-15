 <div id="addprod" class="col-lg-5 col-sm-6 col-xs-12 adminbox">
  <h2>Add Product</h2>
  <form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="type" value="addproduct">
    <div class="form-group">
     <label class="col-sm-4 control-label" for="name">Product Name:</label>
     <div class="col-sm-8">
       <input class="form-control" id="name" name="name" type="text" required>
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-4 control-label" for="price">Price:</label>
     <div class="col-sm-8">
       <input class="form-control" name="price" type="number" pattern="\d*" required>
     </div>
   </div>

   <div class="form-group">
   <label class="col-sm-4 control-label" for="sku">Sku:</label>
     <div class="col-sm-8">
     <input class="form-control" name="sku" type="number" pattern="\d*" required>
     </div>
   </div>

   <div class="form-group">
     <label class="col-sm-4 control-label" for="description">Description:</label>
     <div class="col-sm-8">
       <input class="form-control" name="description" type="text" required>
     </div>
   </div>

   <div class="form-group">
    <label for="upload" class="col-sm-4 control-label">Product Image</label>
    <div class="col-sm-8">
      <input type="file" id="upload" name="imgupload" class="upload">
      <p class="help-block">Upload product image.</p>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button type="submit" class="btn btn-default">Add Product</button>
    </div>
  </div>
</form>
</div>