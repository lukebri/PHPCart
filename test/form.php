<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Form Validation</title>
<style type="text/css">
fieldset { width: 280px; padding: 6px; }
label { float: left; width: 100px; font: 12px Arial; padding: 5px; }
input { margin-bottom: 5px; }
input#hp { margin-bottom: 0; display: none; }
</style>
</head>

<body>

<form id="inputForm" onsubmit="return validateForm();" method="post" action="save.php">
<fieldset>
    <label>First Name:</label><input type="text" name="first_name" /><br />
    <label>Email:</label><input type="text" name="email" /><br />
    <label>Postcode:</label><input type="text" name="postcode" /><br />
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
    <input type="text" id="hp" name="hp" />
    <input type="submit" name="submit" value="Send form" />
    <input type="reset" name="reset" value="Reset" />
</fieldset>
</form>
<script type="text/javascript">
function validateForm() {
  var form = document.forms['inputForm'];
  var expressions = {
    first_name: /^[a-z]+[\-'\s]?[a-z]+$/i,
    email: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/,
    postcode: /^\d{4}$/
  };
  var elCount = form.elements.length;
  for(var i = 0; i < elCount; i++) {
    var field = form.elements[i];
    if(field.type == 'text' && field.name != 'hp') {
      if(!expressions[field.name].test(field.value)) {
        alert('Invalid: ' + field.name.replace('_', ' ').toUpperCase());
        field.focus();
        return false;
      }
    }
  }
}
</script>
</body>
</html>
