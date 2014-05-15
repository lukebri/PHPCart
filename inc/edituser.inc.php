    <div id="edituser" class="col-lg-5 col-sm-6 col-xs-12 adminbox">
      <h2>Edit User</h2>
      <form class="form-horizontal" role="form" method="post" action="">
        <input type="hidden" name="type" value="edituser">
        <div class="form-group">
         <label class="col-sm-4 control-label" for="username">Username:</label>
         <div class="col-sm-8">
           <input class="form-control" id="username" name="username" type="text" value="<?php echo $user{'username'}; ?>" required>
         </div>
       </div>

       <div class="form-group">
         <label class="col-sm-4 control-label" for="firstname">First Name:</label>
         <div class="col-sm-8">
           <input class="form-control" name="firstname" type="text" value="<?php echo $user{'firstname'}; ?>" required>
         </div>
       </div>

       <div class="form-group">
         <label class="col-sm-4 control-label" for="surname">Surname:</label>
         <div class="col-sm-8">
           <input class="form-control"  name="surname" type="text" value="<?php echo $user{'lastname'}; ?>" required>
         </div>
       </div>

       <div class="form-group">
         <label class="col-sm-4 control-label" for="email">Email:</label>
         <div class="col-sm-8">
           <input class="form-control"  name="email" type="email" value="<?php echo $user{'email'}; ?>" required>
         </div>
       </div>
       <p class="help-block" style="
       text-align: center;
       ">Change Password:</p>

       <div class="form-group">
         <label class="col-sm-4 control-label" for="newpass">New Password:</label>
         <div class="col-sm-8">
           <input class="form-control" name="newpass" type="text">
         </div>
       </div>

       <div class="form-group">
         <label class="col-sm-4 control-label" for="currentpass">Current Password:</label>
         <div class="col-sm-8">
           <input class="form-control" name="currentpass" type="text">
         </div>
       </div>



       <div class="form-group">
        <div class="col-sm-offset-2 col-sm-8">
          <button type="submit" class="btn btn-default">Save Changes</button>
        </div>
      </div>
      <input type="hidden" name="id" value="<?php echo $user{'id'}; ?>">
    </form>
  </div> <!-- End edit user -->