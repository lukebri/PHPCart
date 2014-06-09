$(document).ready(function() {

///////// Get how many items in cart session

function cartCount() {
  var ret;
  var request = $.ajax({
    url: "inc/countcart.php",
    type: "POST",
    dataType: "html",
    async:false
  });
    /*
    RETURN ECHO COUNT FROM PHP SESSION VARIABLE FROM
    countcart.php and update items in cart on page
    */
    request.done(function(msg) {
     ret = msg;
    });

    request.fail(function(jqXHR, textStatus) {
      alert( "Can't contact server: " + textStatus );
    });
    return ret;
  }

/////// UPDATE CART COUNT FUNCTION ///////

function updateCount() {
  var request = $.ajax({
    url: "inc/countcart.php",
    type: "POST",
    dataType: "html"
  });
    /*
    RETURN ECHO COUNT FROM PHP SESSION VARIABLE FROM
    countcart.php and update items in cart on page
    */
    request.done(function(msg) {
      $("#cart b").html(msg);
      $("#cartcount").html("(" + msg + ")");
    });

    request.fail(function(jqXHR, textStatus) {
      alert( "Can't contact server: " + textStatus );
    });
  }

  /// Admin product verifications

  $(".editform").submit(function(event){
    event.preventDefault();
    var go = true;
  /// Check SKU and Price are integers, if not don't submit the form, and warn the user.
  var $checkint = $("#sku, #price");
  $.each($checkint, function(index, val) {
    if($.isNumeric( $(val).val() )) {
    } else {
      $(this).notify("Numeric values only", {showAnimation: 'fadeIn', className: 'error', showDuration: 300, arrowShow: true, autoHide: true, autoHideDelay: 3000, elementPosition: 'right middle'});
      go = false;
    }

  });

  if ( go === true ) {
    $('.editform').unbind().submit();
  }
});

  ///// Gets cart total from session
  function getCartTotal() {
    $.ajax({
      type: "POST",
      url: 'inc/getcart.php',
      dataType: 'json',
      async: false,
      data: { type: "getTotal" },
      success: function(data) {
         totalcart = data;
      }
    });
    return totalcart;
  }

///////// PAYPAL SUBMIT CART /////////
$("#paypalcheckout").submit(function(event){
  event.preventDefault();
  /// Get cart session array in JSON via getCart.php
  $.ajax({
    type: "POST",
    url: 'inc/getcart.php',
    dataType: 'json',
    async: false,
    data: { type: "getCartSession"},
    success: function(data) {
      prods = data;
      id = $(".userid").val();
      total = getCartTotal();
      noItems = cartCount();
      inCartCount = $("#cartcount").html().replace(/\(|\)/g, '');;
      ppTotal = $(".amount").val();
    }
  });
  // Check session total vs Cart total
        console.log(noItems);
      console.log(inCartCount);
        if(parseInt(inCartCount) !== parseInt(noItems)) {
    alert("Your cart needs to be refreshed before submitting, as you've added items without refreshing the cart.");
    return;
  }
  if(parseInt(total) !== parseInt(ppTotal)) {
    alert("Your cart needs to be refreshed before submitting, as the cart data does not match what is in your cart.");
    return;
  }
    /// Create order in database via cartSave.php, json encodes product array, price and userid
    // We grab the total price via cart and check later by calcuating the total of the productids in the order in pp.class, to detect and prevent submissions being tampered with in html before submission // This will trigger a fraud warning via email
    $.ajax({
      type: "POST",
      url: "inc/cartSave.php",
      dataType: "json",
      async: false, // We submit the [type] as "Paypal" so cartSave.php can only execute if sent by this script
      data: { type: "paypal", pprice: ppTotal, id: id, products: prods, total: total },
      success: function(data){
        go = true;
        $("#invoice").attr("value", data)
      },
      error: function(data) {
        alert("error");
        go = false;
      }
    });
    // REMOVE ALL ITEMS FROM CART SESSION
    var Del = true;
    $.ajax({
      type: "POST",
      url: "inc/removecart.php",
      data: { DelAll: Del }
    });

    $("#paypalcheckout").notify("Order submitted, please wait for Paypal redirect", {showAnimation: 'fadeIn', className: 'success', showDuration: 300, arrowShow: true, autoHide: true, autoHideDelay: 3000, elementPosition: 'right middle'}).slideUp("fast");

    if (go === true) {
      $('form').unbind().submit();
    }

  });


/////// ADD TO CART
$(".add").click(function(event){
  event.preventDefault();
  $(this).notify("Added to cart", {showAnimation: 'fadeIn', className: 'success', showDuration: 300, arrowShow: true, autoHide: true, autoHideDelay: 3000, elementPosition: 'right middle'});
  // Take value from button to add to cart session array, by posting to addcart.php which adds it to session array ['cart'];
  // Then Pull count from countcart.php

  var $id = $(this).val();
  $.ajax({
    type: "POST",
    url: "inc/addcart.php",
    data: { prodid: $id }
  })
  .done(function() {
    updateCount();
  });

});


//////////// REMOVE FROM CART /////////////

$(".remcart").click(function(event){
  event.preventDefault();

  var chosen = $(this);
  var Del = $(this).attr('value');
  var price = $(chosen).siblings('.pprice').attr("value");

  $.ajax({
    type: "POST",
    url: "inc/removecart.php",
    data: { Del: Del, price: price }
  })
  .done(function() {
    updateCount();
    var changeCount = $(chosen).siblings('.quant').text();
    var sum = changeCount - 1;
    if (sum <= 0) {
    var theprice = parseInt($(chosen).siblings('.pprice').attr("value"));
    var total = parseInt($("#total").html());
    $("#total").html((total - theprice));
    // Also change Paypal form price
    var cur = $(".amount").val();
    var change = cur - theprice;
    $(".amount").val(change);
      $(chosen).parent(".prodcart").slideUp(500, 0).remove();
    } else {
    // Set quantity - 1 to sibling quantity field;
    chosen.siblings('.quant').html((sum - 0));
    // Get price of product removed, and reduce cart total
    var theprice = parseInt($(chosen).siblings('.pprice').attr("value"));
    var total = parseInt($("#total").html());
    $("#total").html((total - theprice));
    // Also change Paypal form price
    var cur = $(".amount").val();
    var change = cur - theprice;
    $(".amount").val(change);
  }
  // If products are empty, set cart value to 0 and hide cart;
  if (!$(".prodcart").length) {
    $("#total").html("0");
    $(".amount").val(0);
    $("#checkout").slideUp(500, 0);
  }

});

});

///////////// LOGIN ////////////
$("#submit").click(function(event){
  event.preventDefault();
  username=$("#lusername").val();
  password=$("#lpassword").val();
  if (!password.trim() || !username.trim()) {
   $(".warning").html("Enter both username or password");
 } else {
  $.ajax({
   type: "POST",
   url: "inc/loginsubmit.php",
   data: "username="+username+"&password="+password,
   success: function(html){
    if(html=='true')
    {
     $("#loginform").fadeTo("slow", 0.0);
     setTimeout(function() {
      window.location.href = "index.php";
    }, 1000);
   }
   else
   {
    $("#topbutton .warning").html("Wrong username or password").fadeIn("normal");
  }
},
beforeSend:function()
{
  $(".ajaxhide").fadeIn("normal");
  $("#topbutton .warning").html("Logging in...");
}
});
}
return false;
});

//////// VALIDATE EMAIL FUNCTION ///////
// http://www.paulund.co.uk/regular-expression-to-validate-email-address//
function validateEmail(email){
  var emailReg = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i);
  var valid = emailReg.test(email);

  if(!valid) {
    return false;
  } else {
    return true;
  }
}

//////// VALIDATE EMAIL FUNCTION ///////
// http://www.paulund.co.uk/regular-expression-to-validate-email-address//
function validateName(name){
  var nameReg = new RegExp(/^[a-z][a-z0-9_\.]{0,24}$/i);
  var valid = nameReg.test(name);

  if(!valid) {
    return false;
  } else {
    return true;
  }
}

///////////// REGISTRATION + VALIDATION /////////////
// First section only handles onchange validation
$("#regform input:text, #regform input:password, #email").blur(function () {
  $this = $(this);
  $val = $this.val();
  $name = $this.attr("name");
  $id = $this.attr('id');

  if ($name == "email") {
   if(!validateEmail($val) ) {
    $this.addClass("error").notify("Not a valid email", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
    return false;
  }
}

if ($name == "num") {
  if($.isNumeric($this.val()) ) {
  } else {
   $this.addClass("error").notify("Numbers only", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
 }
 if($this.val().length == 1) {
  $this.removeClass("error");
} else {
  $this.addClass("error").notify("Enter a number", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
}
}

if ($this.val().length <= 2) {
  if ($id == "submit" || $name == "num") { return; }
  go = false;

  $this.addClass("error").notify("Too short", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
}

if ($this.val().length >= 3) {
  $this.removeClass("error");
}


});


/// FORM SUBMISSION + VALIDATION AGAIN
$("#regform").submit(function(event){
  event.preventDefault();
  var rego = $('#regform').serializeArray();
  go = true;

  $("#regform input:text, #regform input:password, #email").each(function () {
    $this = $(this);
    $this.removeClass("error");
    $val = $this.val();
    $name = $this.attr("name");
    $id = $this.attr('id');

      // Check for special chars in names
      if ($name == "firstname" || $name == "lastname" || $name == "username") {
      /// Underscores allowed, min 2 max 20 chars
      if(!validateName($val) ) {
        $this.addClass("error").notify("No special characters (max 20 chars)", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 2400, elementPosition: 'top'});
      }
    }

      /// Check anti-robot question
      if ($name == "num") {
        if($.isNumeric($val) ) {
        } else {
          go = false;
          $this.addClass("error").notify("Numbers only", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
        }
        if($this.val().length === 1) {
          $this.removeClass("error");
      } else { /// If "num" is not 1 char long (answer is 9)
      go = false;
      $this.addClass("error").notify("Enter a number", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 900, elementPosition: 'top'});
    }
  }

  /// Check for fields less than 2 chars
  if ($this.val().length <= 2) {
    if ($id == "submit" || $name == "num") {
      return;
    } else {
      go = false;
    }
    $this.addClass("error").notify("Too short", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 2400, elementPosition: 'top'});
    // return false;
  }




  if ($name == "email") {
   if(!validateEmail($val) ) {
    $this.addClass("error").notify("Not a valid email", {showAnimation: 'fadeIn', className: 'error', showDuration: 100, arrowShow: true, autoHide: true, autoHideDelay: 2400, elementPosition: 'top'});
  }
}

if ($this.val().length >= 3) {
  $this.removeClass("error");
}

});

 //// Alert on empty fields // Add Error class to input // Remove on keyup

 if (go === true) {
   $.ajax({
     type: "POST",
     url: "register2.php",
     data: rego,
     success: function(data) {
       if (data == "true") {
        $("#regform").fadeTo(700, 0, function() {
          $("#formpage .errorform").fadeOut();
          $(this).addClass("sent").html("Thanks for registering. <br> You'll receive an activation email shortly.").fadeTo(900, 1);
        });
      } else {
        $(".errorform").html(data).fadeTo(1200, 1);
      }
    }
  });

 }
});


});