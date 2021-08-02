
<?php

include('_header.php');

$about_link = "https://www.ibeyonde.com/html/ibeyonde/blogs/archive/raspberry_pi_security_camera.html";
$setup_link = "https://www.ibeyonde.com/html/ibeyonde/blogs/archive/cloudcam_setup.html";

?>

<div class="container">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="login-card">
            <div class="row">

                <div class="col-md-7 col-sm-12 col-xs-12">
                        <img src="<?php echo $logo_img; ?>" width="150" style="opacity: 0.5;">
                </div>
                <div class="sitebuttons col-md-5 col-sm-12 col-xs-12 mt-md-5 mt-xs-0 mt-sm-0">
                    <a href=<?php echo $setup_link; ?> target="main"> <button class="mybutton btn btn-sm"><span class="fa fa-lightbulb"></span> Know more</button></a>
                </div>
                <div class="sitebuttons col-md-5 col-sm-12 col-xs-12 mt-md-5 mt-xs-0 mt-sm-0">
                    <a href=<?php echo $about_link; ?> target="main"> <button class="mybutton btn btn-sm"><span class="fa fa-home"></span> About</button></a>
                </div>
            </div>




            <form role="form" method="post" action="index.php" name="loginform">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text fa fa-user fa-1x" id="basic-addon1"></span>
                    </div>
                    <input type="text" name="user_name" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text fa fa-lock fa-1x" id="basic-addon2"></span>
                    </div>
                    <input type="password" name="user_password" class="form-control" placeholder="Password" aria-label="Recipient's username" aria-describedby="basic-addon2" required>

                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <button type="submit" name="login" class="btn btn-sim1 btn-blk"><h8><?php echo WORDING_LOGIN; ?></h8></button>

                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <a href="password_reset.php"><h8><?php echo WORDING_FORGOT_MY_PASSWORD; ?></h8></a>
                        <h8>|</h8>
                        <a href="register.php"><h8><?php echo WORDING_REGISTER_NEW_ACCOUNT; ?></h8></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
 </div>

