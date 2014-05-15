   <?php if(isset($_SESSION['return']))
   {
    foreach ($_SESSION['return'] as $key => $value) {
      echo '<p class="note">' . $value . '</p>';
    }
    unset($_SESSION['return']);
  }
  ?>

  <?php if (isset($_SESSION['success']) || !empty($success)) : ?>
   <?php
   if (isset($_SESSION['success'])) { $success[] = $_SESSION['success']; }
   echo '<div class="mbox"><strong class="success">Success.</strong>';
   foreach ($success as $key => $value): ?>
   <p class="note">
     <?php echo $value; ?>
   </p>
 <?php endforeach ?>
</div>
<?php unset($_SESSION['success']); ?>
<?php endif ?>


<?php if (isset($_SESSION['errors']) || !empty($errors)) : ?>
 <?php
 if (isset($_SESSION['errors'])) { $errors[] = $_SESSION['errors']; }
 echo '<div class="mbox"><strong class="warning">There are ' . count($errors) . ' errors.</strong>';
 foreach ($errors as $key => $value): ?>
 <p class="note">
   <?php echo $value; ?>
 </p>
<?php endforeach ?>
</div>
<?php unset($_SESSION['errors']); ?>
<?php endif ?>

<?php if (isset($_SESSION['register_ok'])) : ?>
  <p class="note">Please <a href="activate.php?code=<?php echo $_SESSION['register_ok']; ?>">activate</a> your account
  </p>
  <?php unset($_SESSION['register_ok']); ?>
<?php endif ?>