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
  background-color: var(--tb3);
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
  <footer class="fixed-bottom">
    <div class="row">
        <div class="col-md-4 col-6">
            <a href="/terms.html" class="footer-text text-orange">Terms</a>&emsp;|&emsp;
            <a href="/privacy_policy.html" class="footer-text text-orange">Privacy</a>
        </div>
        <div class="col-md-1 d-md-block d-sm-none d-none">
        </div>
        <div class="col-md-3 d-md-block d-sm-none d-none">
        </div>
        <div class="col-md-4 col-6 footer-social">
            <a href="https://twitter.com/agneya2001"><span class="material-icons md-24 orange">tap_and_play</span></a>
            <a href="https://fb.me/ibeyonde"><span class="material-icons md-24 orange">facebook</span></a>
            <a href="https://www.linkedin.com/company/ibeyonde-cloud/"><span class="material-icons md-24 orange">workspace_premium</span></a>
            <a href="https://github.com/gitibeyonde"><span class="material-icons md-24 orange">folder</span></a>
        </div>
    </div>
    </div>
</footer>
<!-- ================ End footer Area ================= -->
<script src="/js/mail-script.js"></script>