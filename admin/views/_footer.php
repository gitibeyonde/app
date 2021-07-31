

<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<div class="alert alert-warning">';
            echo $error;
            echo '</div>';
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="alert alert-info">';
            echo $message;
            echo '</div>';
        }
    }
}
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="alert alert-warning">';
            echo $error;
            echo '</div>';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="alert alert-info">';
            echo $message;
            echo '</div>';
        }
    }
}
if (isset($_GET['message'])){

    echo '<div class="alert alert-info">';
    echo $_GET['message'];
    echo '</div>';
}
//error_reporting(E_ERROR | E_PARSE);
?>

    
    <div class="footer navbar-fixed-bottom">
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <i>&nbsp;&nbsp;IbeyondE 2016-2017</i>
            </div>
             <div class="col-xs-6 col-md-4">
                 <a href="mailto:info@ibeyonde.com"><b>Contact Us</b></a>
             </div>
        </div>
    </div>
</body>
</html>
