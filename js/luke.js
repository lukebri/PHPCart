$(document).ready(function() {

///////// PAYPAL SUBMIT CART /////////
$("#paypalcheckout").submit(function(event){
  event.preventDefault();
  /// Get cart session array JSON via getCart.php

  $.ajax({
    type: "POST",
    url: 'inc/getcart.php',
    dataType: 'json',
    async: false,
    success: function(data) {
      prods = data;
      id = $(".userid").val();
    }
  });

    /// Create order in database, using userid and cartarray
    $.ajax({
      type: "POST",
      url: "inc/cartSave.php",
      dataType: "json",
      async: false,
      data: { type: "paypal", id: id, products: prods },
      success: function(data){
        go = true;
        $("#invoice").attr("value", data)
      },
      error: function(data) {
        console.log(data)
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
    cartCount();
  });

});

/////// UPDATE CART FUNCTION ///////
function cartCount() {
  var request = $.ajax({
    url: "inc/countcart.php",
    type: "POST",
    dataType: "html"
  });
    ///// RETURN ECHO COUNT FROM PHP SESSION VARIABLE FROM countcart.php and add update counter on-page
    request.done(function(msg) {
      $("#cart b").html(msg);
      $("#cartcount").html("(" + msg + ")");
    });

    request.fail(function(jqXHR, textStatus) {
      alert( "Can't contact server: " + textStatus );
    });
  }
//////////// REMOVE FROM CART /////////////

$(".remcart").click(function(event){
  event.preventDefault();

  var chosen = $(this);
  var Del = $(this).attr('value');

  $.ajax({
    type: "POST",
    url: "inc/removecart.php",
    data: { Del: Del }
  })
  .done(function() {
    cartCount();
    var changeCount = $(chosen).siblings('.quant').text();
    var sum = changeCount - 1;

    if (sum == 0) {
      $(chosen).parent(".prodcart").slideUp(500, 0).remove();
    } else {
    // Set quantity - 1 to sibling quant field;
    chosen.siblings('.quant').html((sum - 0));
    // Get price of product removed, and reduce cart total
    var theprice = parseInt($(chosen).siblings('.pprice').attr("value"));
    var total = parseInt($("#total").html());
    $("#total").html((total - theprice));
  }
  if (!$(".prodcart").length) {
    $("#total").html("0");
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
   $("#lusername").notify("Enter both username or password", {showAnimation: 'fadeIn', className: 'error', showDuration: 300, arrowShow: true, autoHide: true, autoHideDelay: 3000, elementPosition: 'bottom middle'});
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
    $("#topbutton .warning").html("Something went wrong.").fadeIn("normal");
    $("#lusername").notify("Wrong username or password", {showAnimation: 'fadeIn', className: 'error', showDuration: 300, arrowShow: true, autoHide: true, autoHideDelay: 3000, elementPosition: 'bottom middle'});
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

///////////// REGISTRATION + VALIDATION /////////////
$("#regform").submit(function(event){
  event.preventDefault();
  var rego = $('#regform').serializeArray();
  var go = true;
  $(rego).each( function( key, value) {
    var inputid = this.name;
    $("#" + inputid).removeClass("error");
    /// check HP is blank and stop script if isn't.
    if (value.name == "hp") {
      if (this.value !== "") {
        alert("hmmm");
        go = false;
      }
      return true;
    }
 //// Alert on empty fields // Add Error class to input // Remove on keyup
 if (value.value.length < 1) {
  var inputid = this.name;
  $("#" + inputid).addClass("error").keyup(function () {
    if( $(this).hasClass("error") ) {
      $(this).removeClass("error");
    }
  });
  go = false;
}
});

  if (go === true) {
    var send = $.ajax({
     type: "POST",
     url: "register2.php",
     data: rego,
     success: function(data) {
       if (data == "true") {
        $("#regform").fadeTo(700, 0, function() {
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

