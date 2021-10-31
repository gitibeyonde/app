<?php
include('_header.php');
?>
<link rel="stylesheet" href="/css/login.css">
<section class="container-fluid h-100 my-login-page">
	<div class="container h-100">
		<div class="row justify-content-md-center h-100">
			<div class="card-wrapper">
				<div class="brand">
					<img src="/img/ibeyonde.jpg" alt="login page">
				</div>
				<div class="card fat">
					<div class="card-body">
						<h4 class="card-title">Register</h4>
						<form method="POST" class="my-login-validation" action="/register.php">

							<div class="form-group">
								<label for="email">E-Mail Address</label>
								<input id="email" type="email" name="user_email" value="<?php echo (isset($_POST['user_email']) ? $_POST['user_email'] : ""); ?>" class="form-control" placeholder="User's Email" aria-label="User Email" aria-describedby="basic-addon2" required>
								<div class="invalid-feedback">
									Your email is invalid
								</div>
							</div>

							<div class="form-group">
								<label for="password">Password</label>
								<input id="password" type="password" name="user_password_new" pattern=".{6,}" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon3" autocomplete="off" required data-eye>
								<div class="invalid-feedback">
									Password is required
								</div>
							</div>

							<div class="form-group">
								<label for="password">Password</label>
								<input id="repeat_password" type="password" name="user_password_repeat" pattern=".{6,}" class="form-control" placeholder="Repeat Password" aria-label="Repeat Password" aria-describedby="basic-addon3" autocomplete="off" required>
								<div class="invalid-feedback">
									Password is required
								</div>
							</div>

                            <img src="/tools/showCaptcha.php" class="mb-3" alt="captcha" />
							<div class="form-group">
                                <input id="captcha" type="text" class="form-control" placeholder="<?php echo WORDING_REGISTRATION_CAPTCHA; ?>" name="captcha" required aria-label="Captcha" aria-describedby="basic-addon5">
                            </div>

							<div class="form-group">
								<div class="custom-checkbox custom-control">
									<input type="checkbox" name="agree" id="agree" class="custom-control-input" required>
									<label for="agree" class="custom-control-label">I agree to the <a href="/terms.html" target="_blank">Terms and Conditions</a></label>
									<div class="invalid-feedback">
										You must agree with our Terms and Conditions
									</div>
								</div>
							</div>

							<div class="form-group m-0">
								<button name="register" type="submit" class="btn btn-prim btn-block">
									Register
								</button>
							</div>
							<div class="mt-4 text-center">
								Already have an account? <a href="login.php">Sign In</a>
							</div>
						</form>
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
include('_footer.php'); ?>