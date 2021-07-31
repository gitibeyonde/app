<?php 

if (!isset($custom_wf)) { ?>

 <div class="row" style="background-color: var(--blue9);padding: 80px;"> 
 <br/>
 <br/>
 <br/>
    <h4 class="selthin">Please choose a App first to publish !</h4>
 </div>
<?php 
    return;
} ?>

<h5 class="selthin">1. Publish</h5>
<h6> Now that you have your own Delta App you can publish it here. You can print QR -code or simply share the link. You can also use messaging to share the link
with your audience/customers.
</h6>
<div class="row" style="background-color: var(- -blue9); padding-bottom: 80px;padding-top: 80px;">

    <div class="col-lg-1 col-md-1"></div>
    <div class="col-lg-5 col-md-5">
        <table style="background-color: white; width: 100%; height: 800px;">
            <tr>
                <td colspan=2 style="height: 300px"><font color=darkgrey size=3>This QR-code and link are for OTP enabled App. Otp validates the phone number and comes handy in communicating with the
                        customer. Please note that OTP-enabled apps will give you access to taking payments and communicating with customer from dashborad. </font></td>
            </tr>
            <tr>
                <td><img src="<?php echo $lg; ?>" width="100px"></td>
                <td><font color=black size=5><?php echo $custom_wf['name']; ?></font></td>
            </tr>
            <tr>
                <td colspan=2></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><img src="http://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $html_otp; ?>&sf=8&ms=r&md=0.8" width="200px"></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><h4>
                        <a href="http://<?php echo $url_otp; ?>"><?php echo $url_otp; ?></a>
                    </h4></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><a class="btn btn-sim1" href="https://simonline.in/views/sms/main_print.php?bot_id=<?php echo $custom_wf['bot_id'];?>" target="_blank">print</a></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-5 col-md-5">
        <table style="background-color: white; width: 100%; height: 800px;">
            <tr>
                <td colspan=2 style="height: 300px"><font color=darkgrey size=3>This QR-code and link are for a plain url to the App. No payment and communication is supported in this mode. </font></td>
            </tr>
            <tr>
                <td><img src="<?php echo $lg; ?>" width="100px"></td>
                <td><font color=black size=5><?php echo $custom_wf['name']; ?></font></td>
            </tr>
            <tr>
                <td colspan=2></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><img src="http://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $html; ?>&sf=8&ms=r&md=0.8" width="200px"></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><h4>
                        <a href="http://<?php echo $url; ?>"><?php echo $url; ?></a>
                    </h4></td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: center;"><a class="btn btn-link" href="#">for testing only</a>></td>
            </tr>
        </table>
    </div>
</div>
<br />
<br />
<br />
<h7>You can now further customize the app and checkout the dashboard for any data collected, by clicking on App Builder button in the top menu above</h7>
