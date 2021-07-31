<style>

<?php
if ($_SESSION["mobile"]){
?>
.footer {
  position: relative;
  left: 0;
  bottom: 0;
  width: 100%;
  text-align: center;
   <?php if ($_SERVER['SERVER_NAME']=="app.ibeyonde.com"){
       echo 'background-color: var(--tb3);;';
    }
    else {
        echo 'background-color: #4e3db6;';
    }
    ?>
}
<?php } else { ?>

.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  text-align: center;
   <?php if ($_SERVER['SERVER_NAME']=="app.ibeyonde.com"){
       echo 'background-color: var(--tb3);;';
    }
    else {
        echo 'background-color: #4e3db6;';
    }
    ?>
}
<?php } ?>
</style>

<div class="row">

 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

</div>
<footer class="footer">
    <div class="container">
<?php
// show potential errors / feedback (from login object)
if (isset ( $login )) {
    if ($login->errors) {
        foreach ( $login->errors as $error ) {
            echo '<div class="alert alert-warning">';
            echo $error;
            echo '</div>';
        }
    }
    if ($login->messages) {
        foreach ( $login->messages as $message ) {
            echo '<div class="alert alert-primary">';
            echo $message;
            echo '</div>';
        }
    }
}
if (isset ( $registration )) {
    if ($registration->errors) {
        foreach ( $registration->errors as $error ) {
            echo '<div class="alert alert-warning">';
            echo $error;
            echo '</div>';
        }
    }
    if ($registration->messages) {
        foreach ( $registration->messages as $message ) {
            echo '<div class="alert alert-primary">';
            echo $message;
            echo '</div>';
        }
    }
}
if (isset ( $_SESSION ['message'] ) && $_SESSION ['message'] != "") {
    echo '<div  class="alert alert-primary">';
    echo $_SESSION ['message'];
    echo '</div>';
    $_SESSION ['message'] = "";
}
// error_reporting(E_ERROR | E_PARSE);
?>
    <footer class="footer section-gap">
         <div class="container">
           <div class="footer-bottom row align-items-center text-center text-lg-left">
            <?php if ($_SERVER['SERVER_NAME']=="app.ibeyonde.com"){
                echo '<p class="footer-text m-0 col-lg-4 col-md-12">ibeyonde &copy;2021</p>';
            }
            else {
                echo '<p class="footer-text m-0 col-lg-4 col-md-12">DeltaCatalog Copyright &copy;2021 </p>';
            }
            ?>
            <div class="footer-text col-lg-4 col-md-12 text-center text-lg-left">
                <a href="/catalog-maker/terms&conditions.html" class="footer-text">Terms</a>&emsp;& &emsp;
                <a href="/catalog-maker/privacy_policy.html" class="footer-text">Privacy</a>
            </div>
            <div class="col-lg-4 col-md-12 text-center text-lg-right footer-social">
                <a href="https://twitter.com/agneya2001"><i class="ti-twitter-alt"></i></a>
                <a href="https://fb.me/ibeyonde"><i class="ti-facebook"></i></a>
                <a href="https://github.com/gitibeyonde"><i class="ti-github"></i></a>
                <a href="https://www.linkedin.com/company/ibeyonde-cloud/"><i class="ti-linkedin"></i></a>
            </div>
        </div>
    </div>
</footer>
<!-- ================ End footer Area ================= -->
<script type="text/javascript" src="/js/popper.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script src="/js/mail-script.js"></script>