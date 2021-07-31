<link rel="stylesheet" href="/js/intl-tel-input/css/intlTelInput.css">
<script src="/js/intl-tel-input/js/intlTelInput.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<style>
.iti {
    width: 100%;
}
.iti__flag {
    background-image: url("/js/intl-tel-input/img/flags.png");
}
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .iti__flag {
      background-image: url("/js/intl-tel-input/img/flags@2x.png");
  }
}
</style>
<?php
// error_log(__ROOT__);
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/sms/SmsLog.php');
require_once (__ROOT__ . '/classes/sms/SmsSend.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$_SESSION['log'] = new Log('info');

$_SESSION['capability'] = "SIM";

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$_SESSION['message']="";
$user_phone = $login->getUserPhone();

$utils= new Utils();
$action="";
if (isset($_GET['action'])){
    $action = $_GET['action'];
    error_log("Action = " .$action);
    if ($action == "save_phone"){
        $user_phone = $_GET['phone'];
        $login = new Login();
        $login->savePhoneNumber($user_phone);
    }
    else if ($action == "otp"){
        $smslog = new SmsLog();
        $count_in_last_10_minutes = $smslog->getOtpNumberSentInLast10Mins($user_phone);
        error_log("count_in_last_10_minutes=$count_in_last_10_minutes");
        //check how many otp messages have been set to this number
        if ($count_in_last_10_minutes > 3){
            $_SESSION['message']="You have exceeded the number of OTP request, Please wait for sometime before retrying.";
        }
        else {
            $otp = SmsSend::sendOTP($user_id, "login",$_SESSION['user_name'], $user_phone);
            error_log(">>>Otp = " .$otp);
            $_SESSION['otp']=$otp;
        }
    }
    else if ($action=="validate"){
        $notp = $_GET['otp'];
        error_log(">>>>OTP values ". $_SESSION['otp']. "=".$notp);
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $notp){
            error_log(">>>OTP validated !");
            $login->saveOtp($notp);
            $action="validated";
        }
        else {
            error_log(">>>OTP validation failed ". $_SESSION['otp']. $notp);
            $_SESSION['message'] = "OTP validation failed, please try again !";
            $action="otp";
        }
    }
}

include (__ROOT__ . '/views/_header.php');
?>
<body>
    <main>
    <div class="album py-5 bg-none">
        <div class="container">
               <h2><?php echo $user_name; ?>, please validate your phone number !</h2>
            <hr/>
            
            <table width=100%>
            <tr>
            <td>
            </td>
            </tr>
            <tr>
            <td>
            <?php if ($action != "validated") { ?>
              <div class="login-card">
                <?php if ($action == "change_phone") {?>
               <form class="form-inline" action="/index.php?view=<?php echo MAIN_VIEW; ?>" method="get"  onsubmit="return onSubmitTel()">
                    <input type="tel" id="phone" class="form-control"  name="phone" required>
                    <button type="submit" name="action" value="save_phone" class="btn btn-sim1">Update</button>
               </form>
               <?php } else { ?>
                    <h5 class="card-title"><?php echo $user_phone; ?>
                        <a href="/index.php?view=<?php echo MAIN_VIEW; ?>&action=change_phone" class="btn btn-sim1">
                            Edit</a></h5>
               <?php  } ?>
                <p id="show_error" class="card-text"><?php echo $_SESSION['message']; ?></p>
                <a href="/index.php?view=<?php echo MAIN_VIEW; ?>&action=otp" class="btn btn-sim1">Send Otp</a>
            <?php } else { ?>
              <div class="card-header">
                <h2><?php echo $user_name; ?>, you have validated your phone number !</h2>
              </div>
              <div class="card-body">
                <h5 class="card-title">Validated !</h5>
                <p class="card-text">Please, reload the page..</p>
                <a href="/index.php?view=<?php echo MAIN_VIEW; ?>" class="btn btn-sim1">Reload</a>
            <?php } ?>
              </div>
            </div>
            </td>
            </tr>
            <tr>
            <td>
              <br/>
              <br/>
            </td>
            </tr>
            <tr>
             <td>
            <?php if ($action == "otp") { ?>
                <h3>OTP arriving on your mobile in<img src="/img/20-seconds.gif" width="150px">Seconds.</h3>
               <form class="form-inline" action="/index.php?view=<?php echo MAIN_VIEW; ?>" method="get" onsubmit="return onSubmit()">
                <div class="form-group">
                  <label for="name">Verify Otp:</label>
                  <input id="otp" type=text class="form-control"  name="otp"/>
                      <button type="submit" name="action" value="validate" class="btn btn-sim1">Validate</button>
                </div>
               </form>
            <?php } ?>
             </td>
            </tr>
        </table>
        </div>
    </div>
    </main>
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

function onSubmit(){
    var otp=$('#otp').val();
    if (otp.length == 6 && otp == parseInt(otp, 10)){
        return true;
    }
    else {
        $('#show_error').text("OTP should have be 6 digits");
        return false;
    }
}
</script>  
<?php include (__ROOT__ . '/views/_footer.php');?>
