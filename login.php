<?php
if (!defined('__ROOT__')) define ( '__ROOT__', dirname ( __FILE__ ) ) ;
include (__ROOT__ . '/views/_header.php');
include (__ROOT__ . '/config/config.php');
?>
<div class="container top">
    <div class="row">
        <div class="col-lg-12 col-md-12 d-lg-block d-md-block d-sm-none d-none" style="height: 5vh;"></div>
    </div>
    <div class="row">
        <div class="col-lg-1 col-md-1 d-lg-block d-md-block d-sm-none d-none"></div>
        <div class="col-lg-4 col-md-4 col-12 col-sm-12">
            <form role="form" method="post" action="/index.php?<?php echo $_SERVER['QUERY_STRING'] == "view=logout_view" ? "" : $_SERVER['QUERY_STRING']; ?>" name="loginform">
               <div class="card card-feature text-center text-lg-left mb-4 mb-lg-0">
                    <h3 class="card-feature__title">Login</h3>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"></div>
                        <input type="text" name="user_name" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"></div>
                        <input type="password" name="user_password" class="form-control" placeholder="Password" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 col-sm-12">
                            <button type="submit" name="login" class="btn btn-block btn-sim4">
                                <h7><?php echo WORDING_LOGIN; ?></h7>
                            </button>
                        </div>
                    </div>

                    <br/>
                    <h7>
                    <a href="/password_reset.php">Forgot Password</a>
                    |
                    <a href="/register.php">Signup</a>
                    </h7>
                </div>
            </form>
        </div>
        <div class="col-lg-2 col-md-2 d-lg-block d-md-block d-sm-none d-none"></div>
        <div class="col-lg-4 col-md-4 d-lg-block d-md-block d-sm-none d-none">
         	<img src="/img/ibeyonde.jpg" class="img-fluid" width="400px">
        </div>
        <div class="col-lg-1 col-md-1 d-lg-block d-md-block d-sm-none d-none"></div>
    </div>
</div>
<?php
include (__ROOT__ . '/views/_footer.php');
?>
</body>
