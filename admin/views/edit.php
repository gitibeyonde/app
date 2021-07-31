<?php include('_header.php'); ?>

<div class="container">
    <div class="row">

        <h1><font color=purple><?php echo $_SESSION['user_name']; ?> </font> </h1>
        <br /> <font color=green><?php echo WORDING_EDIT_YOUR_CREDENTIALS; ?></font>
        <br /> <br />
        <hr />
        <h3><font color=blue>Change Email</font></h3>
        <form method="post" action="edit.php" name="user_edit_form_email">
            <fieldset>
                <div class="form-group">
                    <label for="user_email"><?php echo WORDING_NEW_EMAIL; ?></label> <input
                        id="user_email" type="email" name="user_email" required /><br /> (<?php echo WORDING_CURRENTLY; ?>: <?php echo $_SESSION['user_email']; ?>)
      </div>
                <input type="submit" name="user_edit_submit_email" class="btn btn-lg btn-primary btn-block"
                    value="<?php echo WORDING_CHANGE_EMAIL; ?>" />
            </fieldset>
        </form>

        <hr />
        <h3><font color=blue>Change Password</font></h3>
        <font color=red>CAUTION: Changing password will require you to re-register your devices !!</font>
        <br/>
        <br/>
        <form method="post" action="edit.php" name="user_edit_form_password">
            <fieldset>
                <div class="form-group">
                    <label for="user_password_old"><?php echo WORDING_OLD_PASSWORD; ?></label>
                    <input id="user_password_old" type="password"
                        name="user_password_old" autocomplete="off" />
                </div>

                <div class="form-group">
                    <label for="user_password_new"><?php echo WORDING_NEW_PASSWORD; ?></label>
                    <input id="user_password_new" type="password"
                        name="user_password_new" autocomplete="off" />
                </div>

                <div class="form-group">
                    <label for="user_password_repeat"><?php echo WORDING_NEW_PASSWORD_REPEAT; ?></label>
                    <input id="user_password_repeat" type="password"
                        name="user_password_repeat" autocomplete="off" />
                </div>

                <input type="submit" class="btn btn-lg btn-primary btn-block" name="user_edit_submit_password" onSubmit="alert('After password change, re-register all your devices ?');"
                    value="<?php echo WORDING_CHANGE_PASSWORD; ?>" />
            </fieldset>
        </form>
        <hr />

        <!-- backlink -->
        <a href="index.php"><?php echo WORDING_BACK_TO_LOGIN; ?></a>


    </div>
</div>
<?php include('_footer.php'); ?>
