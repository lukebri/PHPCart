<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Form Validation</title>
  <style type="text/css">
    fieldset { width: 280px; padding: 6px; }
    label { float: left; width: 100px; font: 12px Arial; padding: 5px; }
    input { margin-bottom: 5px; }
    #result { display:none; }
    input#hp { margin-bottom: 0; display: none; }
  </style>
</head>

<body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form id="contact" method="post">
  <fieldset>
    <label>Your Name</label><br />
    <input name="name" id="name" type="text">  
    <label>Your Email</label><br />
    <input name="email" id="email" type="text">  
    <label>Your Question</label><br />
    <textarea rows="10" name="text" id="text" ></textarea>
  </fieldset>
  <input type="submit" value="Send Message" name="submit">
  <p class="success" style="display:none">Your message has been sent successfully.</p>
  <p class="error" style="display:none">E-mail must be valid and message must be longer than 100 characters.</p>
</form>
  <script type="text/javascript">

     // Contact Form
$("#contact").submit(function(e){
  e.preventDefault();
  var name = $("#name").val();
  var email = $("#email").val();
  var text = $("#text").val();
  var dataString = 'name=' + name + '&email=' + email + '&text=' + text;
  function isValidEmail(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
  };

  if (isValidEmail(email) && (text.length > 100) && (name.length > 1)){
    $.ajax({
    type: "POST",
    url: "submit.php",
    data: dataString,
    success: function(){
      $('.success').fadeIn(1000);
      alert($result);
    }
    });
  } else{
    $('.error').fadeIn(1000);
  }

  return false;
});
</script>

</body>
</html>
