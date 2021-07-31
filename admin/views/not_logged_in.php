<?php include('_header.php'); ?>

<style>
.form-signin {
  max-width: 380px;
  padding: 15px 35px 45px;
  margin: 0 auto;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,0.1);  

  .form-signin-heading,
	.checkbox {
	  margin-bottom: 30px;
	}

	.checkbox {
	  font-weight: normal;
	}

	.form-control {
	  position: relative;
	  font-size: 16px;
	  height: auto;
	  padding: 10px;
		@include box-sizing(border-box);

		&:focus {
		  z-index: 2;
		}
	}

</style><div class="conatiner">
<div class="row">
<div class="col-md-4 col-sm-4"></div>
<div class="login-form-div col-md-4 col-sm-4">
<form  role="form" class="form-group form-signin" method="post" action="index.php" name="loginform">
	 
	 <div class="row">
		<h2 class="form-signin-heading">IbeyondE login</h2>
	 </div>
	 <div class="row">	
		<input id="user_name" class="form-control" type="text" name="user_name" autocapitalize="none" required placeholder="Username" />
	 </div>	
	 <br>
	 <div class="row">
		<input id="user_password" type="password" class="form-control" name="user_password" autocapitalize="none" autocomplete="off" required  placeholder="Password"/>
	 </div>
	 <div class="row">
		 <input type="checkbox" id="user_rememberme" name="user_rememberme" checked="checked"  />
		 <label for="user_rememberme" class="content-label"><?php echo WORDING_REMEMBER_ME; ?></label>
	 </div>
	 <div class="row"> 
     <input type="submit"  class="btn btn-lg btn-primary btn-block" name="login" value="<?php echo WORDING_LOGIN; ?>" />
	 </div>
	 
	 <a href="register.php"><?php echo WORDING_REGISTER_NEW_ACCOUNT; ?></a>
	 </br>
     </br>
	 <a href="password_reset.php"><?php echo WORDING_FORGOT_MY_PASSWORD; ?></a>
     </br>
     </br>
     <a href="https://app.ibeyonde.com"><?php echo WORDING_USER_PORTAL; ?></a>
</form>
</div>
</div>
</div>
<?php include('_footer.php'); ?>