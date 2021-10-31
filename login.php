<link rel="stylesheet" href="/css/login.css">
<?php
if (!defined('__ROOT__')) define ( '__ROOT__', dirname ( __FILE__ ) ) ;
include (__ROOT__ . '/views/_header.php');
require_once(__ROOT__ . '/config/config.php');
?>
<script src="js/login.js"></script>
<div class="container top">
  <section class="container-fluid h-100 my-login-page">
    <div class="container h-100">
        <div class="row justify-content-md-center h-100">
            <div class="card-wrapper">
                <div class="brand">
                    <img src="/img/ibeyonde.jpg"" alt="pagemight html builder login page">
                </div>
                <div class="card fat">
                    <div class="card-body">
                        <h4 class="card-title">Login</h4>
                          <form role="form" method="post" class="my-login-validation" novalidate="" action="/index.php?<?php echo $_SERVER['QUERY_STRING'] == "view=logout_view" ? "" : $_SERVER['QUERY_STRING']; ?>" name="loginform">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" type="username" name="user_name" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required autofocus>
                                <div class="invalid-feedback">
                                    Username is invalid
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password
                                    <a href="/password_reset.php" class="float-right">
                                        Forgot Password?
                                    </a>
                                </label>
                               <input type="password" name="user_password" class="form-control" placeholder="Password" aria-label="Recipient's username"
                                           aria-describedby="basic-addon2" required data-eye="" autocomplete>
                                <div class="invalid-feedback">
                                    Password is required
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                                    <label for="remember" class="custom-control-label">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group m-0">
                                <button type="submit" name="login" value="login" class="btn btn-info btn-block">
                                    Login
                                </button>
                            </div>
                            <div class="mt-4 text-center">
                                Don't have an account? <a href="/register.php">Create One</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		<div class="footer">
			Copyright &copy; 2021 &mdash; Ibeyonde
		</div>
    </div>
</section>
</div>
<?php
include (__ROOT__ . '/views/_footer.php');
?>
</body>
