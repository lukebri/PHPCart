   // Use the return session for some errors that return a message
   <?php if(isset($_SESSION['return']))
   {
    foreach ($_SESSION['return'] as $key => $value) {
      echo '<p class="note">' . $value . '</p>';
    }
    unset($_SESSION['return']);
  }
  ?>
  // Use this to remind user on homepage of registration submission (may be depreciated)
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

  // The generic session error display
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