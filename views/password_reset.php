<?php
include ('_header.php');
?>
<div class="container top">
    <div class="row">
        <div class="col-lg-12 col-md-12 d-lg-block d-md-block d-sm-none d-none" style="height: 5vh;"></div>
    </div>

    <div class="main col-md-8 col-sm-12 col-xs-12">
        <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h3 class="card-feature__title">Reset Password</h3>
                </div>
            </div>
            <br />

            <?php if ($login->passwordResetLinkIsValid() == true) { ?>
            <form role="form" class="form" method="post" action="/password_reset.php" name="new_password_form">
                <fieldset>
                    <input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
                    <input type='hidden' name='user_password_reset_hash'
                        value='<?php echo $_GET['verification_code']; ?>' />
                    <div class="form-group">
                        <label class="pull-left" for="user_password_new"><?php echo WORDING_NEW_PASSWORD; ?></label>
                        <input id="user_password_new" type="password" class="form-control"
                            name="user_password_new" pattern=".{6,}" required autocomplete="off" autocapitalize="none" />
                    </div>
                    <div class="form-group">
                        <label class="pull-left" for="user_password_repeat"><?php echo WORDING_NEW_PASSWORD_REPEAT; ?></label>
                        <input id="user_password_repeat" class="form-control" type="password"
                            name="user_password_repeat" pattern=".{6,}" required autocomplete="off" autocapitalize="none" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-sim4 btn-block" name="submit_new_password" value="<?php echo WORDING_SUBMIT_NEW_PASSWORD; ?>" />
                    </div>
                </fieldset>
            </form>


            <?php } else { ?>

            <form class="form" role="form" method="post" action="/password_reset.php" name="password-reset_form">
                <div class="input-group mb-3">
                    <div class="input-group-prepend"></div>
                    <input type="text" name="user_name" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <button type="submit" name="request_password_reset" class="btn btn-sim4 btn-block">
                            <h7>Send Reset Link</h7>
                        </button>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <a href="/login.php"><small style="color: white; margin-left: 5px;" class="pull-right"><h7><?php echo WORDING_BACK_TO_LOGIN; ?></h7></small></a>
                    </div>
                </div>
            </form>
            <?php } ?>

            <h7> <a href="/login.php"><h7>Login</h7></a> | <a href="register.php">Signup</a> </h7>
        </div>
    </div>
</div>
<?php
include ('_footer.php');
?>
</body>
</html>