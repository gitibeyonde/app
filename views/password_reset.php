<?php
include ('_header.php');
?>
<link rel="stylesheet" href="/css/login.css">
<section class="container-fluid h-100 my-login-page">
	<div class="container h-100">
		<div class="row justify-content-md-center align-items-center h-100">
			<div class="card-wrapper">
				<div class="brand">
					<img src="/img/ibeyonde.jpg" alt="pagemight html builder login page">
				</div>
				<div class="card fat">
					<div class="card-body">


            <?php if ($login->passwordResetLinkIsValid() == true) { ?>
					<h4 class="card-title">Reset Password</h4>

            		<form role="form" class="my-login-validation" method="post" action="/password_reset.php" name="new_password_form">
                        <input type='hidden' name='user_email' value='<?php echo $_GET['user_email']; ?>'>
                        <input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>'>
						<div class="form-group">
							<label for="new-password">New Password</label>
							<input id="user_password_new" type="password" class="form-control"
                            			name="user_password_new" pattern=".{6,}" required autocomplete="off" autocapitalize="none" autofocus data-eye>
							<div class="invalid-feedback">
								Password is required
							</div>
							<div class="form-text text-muted">
								Make sure your password is strong and easy to remember
							</div>
						</div>
						<div class="form-group">
							<label for="repeat-password">Repeat Password</label>
							<input id="user_password_repeat" type="password" class="form-control"
                            			name="user_password_repeat" pattern=".{6,}" required autocomplete="off" autocapitalize="none" autofocus data-eye>
							<div class="invalid-feedback">
								Password is required
							</div>
							<div class="form-text text-muted">
								Make sure your password is strong and easy to remember
							</div>
						</div>
						<div class="form-group m-0">
							<button type="submit" name="submit_new_password" value="Submit new password" class="btn btn-warning btn-block">
								Reset Password
							</button>
						</div>
					</form>

            <?php } else { ?>
					<h4 class="card-title">Forgot Password</h4>
					<form class="my-login-validation" role="form" method="post" action="/password_reset.php" name="password-reset_form">
						<div class="form-group">
							<label for="email">E-Mail Address</label>
							<input id="email" type="email" name="user_email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" required autofocus>
							<div class="invalid-feedback">
								Email is invalid
							</div>
							<div class="form-text text-muted">
								By clicking "Reset Password" we will email a password reset link
							</div>
						</div>

						<div class="form-group m-0">
							<button type="submit" name="request_password_reset" class="btn btn-info btn-block">
								Reset Password
							</button>
						</div>
					</form>
            <?php } ?>
					</div>

                    <div class="row">
                       <div class="col center-block text-center">
                    		<h7> <a href="/login.php">Sign In</a>
                                        &nbsp;|&nbsp;
                               <a href="/register.php">Sign Up</a> </h7>
                       </div>
                    </div>
				</div>
                </div>
        		<div class="footer">
        			Copyright &copy; 2021 &mdash; Ibeyonde
        		</div>
			</div>
		</div>
	</div>
</section>
<script src="/js/login.js"></script>
<?php
include ('_footer.php');
?>