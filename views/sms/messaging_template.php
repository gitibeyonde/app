<?php
include(__ROOT__.'/views/_header.php');

?>   

<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>
      <br/>
      <h3>Goto</h3>
      <br/>
       <div class="row">
        <div class="col-lg-4 col-md-4">
            <form class="form-inline" action="/index.php"  method="get">
            <input type=hidden name=view value="<?php echo MESSAGE_TEMPLATE_SMS; ?>">
            <button type="submit" name="submit" value="template_sms" style="background: transparent; border: 0;">
            <h4>SMS Template</h4></button>
            </form>
        </div>
        <div class="col-lg-4 col-md-4">
            <form action="/index.php"  method="get">
            <input type=hidden name=view value="<?php echo MESSAGE_TEMPLATE_EMAIL; ?>">
            <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
            <h4>EMAIL Template</h4></button>
            </form>
        </div>
      
     </div> 
</div>

<?php include(__ROOT__.'/views/_footer.php'); ?>
 </body>
  
