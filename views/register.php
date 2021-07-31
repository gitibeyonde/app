<link rel="stylesheet" href="/js/intl-tel-input/css/intlTelInput.css">
<script src="/js/intl-tel-input/js/intlTelInput.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<style>
.iti__flag {background-image: url("/js/intl-tel-input/img/flags.png");}

@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .iti__flag {background-image: url("/js/intl-tel-input/img/flags@2x.png");}
}
</style>
<?php
include('_header.php');

include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/core/Icons.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');

$_SESSION['log'] = new Log('debug');

$WFDB = new WfMasterDb();
$sharedwf = $WFDB->getSharedWorkflow();

$min = new SmsMinify();

$SI = new SmsImages();
$logo= "/img/ico192.png";

$Icon = new Icons();
?>
<main>
<div class="container top">
   <div class="row">
    <div class="col-lg-1 col-md-1 d-lg-block d-md-block d-sm-none d-none" style="height: 20px;">
    </div>
   </div>
   <div class="row" style="height: 550px;">
    <div class="col-lg-1 d-lg-block d-md-none d-sm-none d-none">
    </div>
    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
         <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                        <h3 class="card-feature__title">Signup</h3>
                </div>
            </div>
            <form method="post" action="/register.php" onsubmit="return onSubmitTel();">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    </div>
                    <input type="text" name="user_name" value="<?php echo (isset($_POST['user_name']) ? $_POST['user_name'] : ""); ?>" class="form-control" pattern="[a-zA-Z0-9]{2,64}" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    </div>
                    <input type="email" name="user_email" value="<?php echo (isset($_POST['user_email']) ? $_POST['user_email'] : ""); ?>" class="form-control" placeholder="User's Email" aria-label="User Email" aria-describedby="basic-addon2" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    </div>
                    <input type="tel" id="phone" name="user_phone" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    </div>
                    <input type="password" name="user_password_new" pattern=".{6,}" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon3" autocomplete="off" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    </div>
                    <input type="password" class="form-control" name="user_password_repeat" pattern=".{6,}" placeholder="Repeat Password" aria-label="Repeat Password" aria-describedby="basic-addon4" autocomplete="off" required>
                </div>


                <img src="/tools/showCaptcha.php" class="mb-3" alt="captcha" />
                <br>
                <div class="input-group mb-3">
                    <!-- captcha -->
                     <div class="input-group-prepend">
                    </div>
                    <input type="text" class="form-control" placeholder="<?php echo WORDING_REGISTRATION_CAPTCHA; ?>" name="captcha" required aria-label="Captcha" aria-describedby="basic-addon5" />
                </div>

                <div class="row">
                    <div class="col-md-5 align-self-md-start col-xs-12 col-sm-12">
                        <button name="register" type="submit" class="btn btn-block btn-sim4"><h7><?php echo WORDING_REGISTER; ?></h7></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 align-self-md-end col-xs-12 col-sm-12">
                        <h7>
                            <a href="/password_reset.php">Forgot Password</a>
                                |
                            <a href="/login.php">Login</a>
                         </h7>
                        <br/>
                        <br/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-5 col-md-6 d-lg-block d-md-block d-sm-none d-none" style="text-align: center;">
             <iframe src="https://1do.in/willywonka"
					class="embed-responsive-item" scrolling="yes" frameborder="0"
					width="100%" height="100%"></iframe>
			<a href="https://1do.in/willywonka" target="_blank">https://1do.in/willywonka</a>
    </div>
 </div>
</div>
</main>
<?php
include('_footer.php'); ?>
</body>
<script>
var input = document.querySelector("#phone");
window.intlTelInput(input, {
          preferredCountries: ["in", "us", "gb", "cn", "br", "fr", "ar", "ru" ],
          separateDialCode: true,
          initialCountry: "auto",
          geoIpLookup: function(callback) {
            $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              callback(countryCode);
            });
          },
        utilsScript: "/js/intl-tel-input/js/utils.js"
});

function onSubmitTel(){
    var input = document.querySelector('#phone');
    var iti = window.intlTelInputGlobals.getInstance(input);
    var numberType = iti.getNumberType();
    console.log(numberType);

    var isValid = iti.isValidNumber();
    console.log(isValid);

    var number = iti.getNumber();
    if (numberType !== intlTelInputUtils.numberType.MOBILE && numberType !== intlTelInputUtils.numberType.FIXED_LINE_OR_MOBILE) {
        alert("Not a mobile number " + number);
        return false;
    }
    var numberType = iti.getNumberType();

    if (iti.isValidNumber()){
        var number = iti.getNumber();
        number = number.substring(1);
        input.value = number;
        console.log(number);
        return true;
    }
    else {
        var error = iti.getValidationError();
        if (error === intlTelInputUtils.validationError.TOO_SHORT) {
            alert("Number too short " + number);
        }
        else {
            alert("Invalid Number " + number);
        }
        return false;
    }
}

</script>
