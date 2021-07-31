<?php include('_header.php'); ?>

<div class="container">
    <div class="row">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-4 col-xs-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                          <h3><i class="fa fa-lock fa-4x"></i></h3>
                          <h2 class="text-center">Forgot Password?</h2>
                          <p>You can reset your password here.</p>
                            <div class="panel-body">

<?php if ($login->passwordResetLinkIsValid() == true) { ?>
<form role="form"  class="form" method="post" action="password_reset.php" name="new_password_form">
<fieldset>
    <input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
    <input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />
	<div class="form-group">
	    <label  class="pull-left" for="user_password_new"><?php echo WORDING_NEW_PASSWORD; ?></label>
	    <input id="user_password_new" type="password" class="form-control" name="user_password_new" pattern=".{6,}" required autocomplete="off"  autocapitalize="none"  />
	</div>
	<div class="form-group">
	    <label  class="pull-left" for="user_password_repeat"><?php echo WORDING_NEW_PASSWORD_REPEAT; ?></label>
	    <input id="user_password_repeat" class="form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off"  autocapitalize="none" />
	</div>
	<div class="form-group">

	    	<input type="submit" class="btn btn-sim4 btn-block" name="submit_new_password" value="<?php echo WORDING_SUBMIT_NEW_PASSWORD; ?>" />

    </div>
	 </fieldset>
</form>
<!-- no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form -->
<?php } else { ?>
                              <form class="form" role="form" method="post" action="password_reset.php" name="password_reset_form">
                                <fieldset>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>

                                     <input id="user_name" class="form-control" placeholder="username" type="text" name="user_name"  autocapitalize="none" required />
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <input class="btn btn-sim4 btn-block" name="request_password_reset" value="<?php echo WORDING_RESET_PASSWORD; ?>"  type="submit">
                                  </div>
                                </fieldset>
                              </form>


<?php } ?>
<a href="index.php"><?php echo WORDING_BACK_TO_LOGIN; ?></a><br/>
<a href="password_reset.php"><?php echo WORDING_FORGOT_MY_PASSWORD; ?></a>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('_footer.php'); ?>