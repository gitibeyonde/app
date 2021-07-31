<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/libraries/aws.phar');
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');


$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];



if (isset($_POST['action']) && $_POST['action'] == "email"){
    error_log("Update Email");
}
else if (isset($_POST['action']) && $_POST['action'] == "phone"){
    error_log("Update Phone");
}
else if (isset($_POST['action']) && $_POST['action'] == "password"){
    $user_password_old = $_POST['user_password_old'];
    $user_password_new = $_POST['user_password_new'];
    $user_password_repeat = $_POST['user_password_repeat'];
    if ($user_password_new != $user_password_repeat){
        $_SESSION['message'] = "The new and repeat password do not match";
    }
    else if (strlen($user_password_new) < 5 ){
        $_SESSION['message'] = "The password should be 5 chars or more.";
    }
    else if ($user_password_old == $user_password_new){
        $_SESSION['message'] = "The old and new password are same.";
    }
    else {
        $mysql = new Mysql();
        $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
        $db_pass_hash=$mysql->selectOne(sprintf("select user_password_hash from users where user_id=%d", $user_id));
        if (password_verify($user_password_old,$db_pass_hash)){
            $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

            $mysql->changeRow(sprintf("update users set user_password_hash=%s where user_id=%d",
                                             $mysql->quote($user_password_hash), $user_id));
            $_SESSION['message'] = "Password updated";
        }
        else {
            $_SESSION['message'] = "Old password is incorrect";
        }
    }
}
?>
<body>
<div class="container top">
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
  <br/>
    <!--
      <h3>Change Email Address</h3>
      <h8>Changing email will require revalidation before you can access your account !!</h8>
      <div class="row" style="padding: 20px;">
            <form method="post" action="/index.php?view=<?php echo USER_ACCOUNT; ?>" name="user_edit_form_email">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="fa fa-user fa-fw" id="basic-addon4"></span>
                </div>
                <input type="text" id="currentEmail" size=60 class="form-control" value="<?php echo $_SESSION['user_email']; ?>" aria-label="CurrentEmail" aria-describedby="basic-addon4" readonly>
                </div>

                <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="fa fa-envelope fa-fw" id="basic-addon5"></span>
                </div>
                <input type="text" id="newEmail" type="email" size=60 name="user_email" class="form-control" placeholder="New Email" aria-label="New Email" aria-describedby="basic-addon5" required>

                </div>
                <div class="input-group mb-3">

                <input type="hidden" name="view" value="<?php echo USER_ACCOUNT; ?>">

                <button type="submit" name="action" value="email"  class="btn-sim4">Update Email</button>

                </div>
            </form>
       </div>

      <h3>Change Phone Number</h3>
      <h8>Changing phone will require revalidation before you can access your account !!</h8>
      <div class="row" style="padding: 20px;">
            <form method="post" action="/index.php?view=<?php echo USER_ACCOUNT; ?>" name="user_edit_form_phone">
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="fa fa-mobile fa-fw" id="basic-addon4"></span>
                </div>
                <input type="text" id="currentPhone" class="form-control" value="<?php echo $_SESSION['user_phone']; ?>" aria-label="CurrentPhone" aria-describedby="basic-addon4" readonly>
                </div>

                <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="fa fa-mobile-alt fa-fw" id="basic-addon5"></span>
                </div>
                <input type="text" id="newPhone" type="phone" name="user_phone" class="form-control" placeholder="New Phone" aria-label="New Phone" aria-describedby="basic-addon5" required>

                </div>
                <div class="input-group mb-3">

                <button type="submit" name="action" value="phone"  class="btn-sim4">Update Phone</button>

                </div>
            </form>
         </div>


          -->

      <h4>Change Password</h4>
      <div class="row" style="padding: 20px;">
            <form method="post" action="/index.php?view=<?php echo USER_ACCOUNT; ?>" name="user_edit_form_password">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="fa fa-key fa-fw" id="basic-addon7"></span>
                    </div>
                    <input id="user_password_old" type="password" class="form-control" placeholder="Current Password" aria-label="Username" aria-describedby="basic-addon7" name="user_password_old" autocomplete="off">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="fa fa-key fa-fw" id="basic-addon8"></span>
                    </div>
                    <input id="user_password_new" type="password" class="form-control" placeholder="New Password" aria-label="Recipient's username" aria-describedby="basic-addon8"  name="user_password_new" autocomplete="off">

                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="fa fa-key fa-fw" id="basic-addon9"></span>
                    </div>
                    <input id="user_password_repeat" type="password" class="form-control" placeholder="Confirm Password" aria-label="Recipient's username" aria-describedby="basic-addon9" name="user_password_repeat" autocomplete="off">

                </div>
                <div class="input-group mb-3">

                <button type="submit" name="action" value="password" class="btn-sim4">Update Password</button>

                </div>
            </form>
         </div>
  </div>
<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>
