<?php
session_start();
$pageTitle = "SurfShop | Contact";
require_once 'inc/header.php';
?>
<section id="content">
  <div class="container">
    <div class="row" id="contact">

      <section class="col-lg-6 col-sm-6 col-xs-12">
        <div id="sent">Thank you, I'll be in contact as soon as I can!</div>
        <p class="col_title">CONTACT ME</p>
        <form role="form" method="post" action="#">

            <div class="form-group">
            <input type="text" name="first_name" class="form-control" placeholder="Enter name *" required/>
            </div>

            <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Enter email *" required/>
            </div>

            <div class="form-group dropdown">
             <label for="reason" class="reason">What's this about?</label>
               <select name="reason" id="reason">
                  <option value="default">Not listed</option>
                  <option value="work">A product question</option>
                  <option value="previouswork">Orders</option>
                  <option value="accounts">Accounts</option>
               </select>
             </div>

          <div class="form-group">
            <textarea rows="3" placeholder="Message *" name="message" required/></textarea>
          </div>

          <button type="submit" class="btn btn-default">SEND MAIL</button>
        </form>
      </section>

    </div> <!-- /row contact -->

 </div>  <!-- /container content -->

 <?php
require_once 'inc/footer.php';
?>
</body>
</html>
