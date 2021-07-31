<?php include('_header.php'); ?>

<main>
    <div class="container"  style="padding-top: 100px;">

        <h4><?php echo $_SESSION['user_name']; ?></h4>
        <hr/>
        <div class="main col-md-12 col-sm-12 col-xs-12">
            <div class="profile-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-email-tab" data-toggle="pill" href="#v-pills-changeEmail" role="tab" aria-controls="v-pills-changeEmail" aria-selected="true">Change Email</a>
                            <a class="nav-link" id="v-pills-phone-tab" data-toggle="pill" href="#v-pills-changePhone" role="tab" aria-controls="v-pills-changePhone" aria-selected="false">Change Mobile Number</a>
                            <a class="nav-link" id="v-pills-password-tab" data-toggle="pill" href="#v-pills-changePassword" role="tab" aria-controls="v-pills-changePassword" aria-selected="false">Change Password</a>
                            <!--<a class="nav-link" id="v-pills-password-tab" data-toggle="pill" href="#v-pills-password" role="tab" aria-controls="v-pills-password" aria-selected="false">Password</a>-->

                        </div>

                    </div>


                    <span class="mr-auto"></span>

                    <div class="col-md-6">
                        <div class="tab-content" id="v-pills-tabContent">


                            <div class="tab-pane fade show active" id="v-pills-changeEmail" role="tabpanel" aria-labelledby="v-pills-email-tab">
                            <font color=red>CAUTION: Changing email will require revalidation before you can access your account !!</font>
                                <form method="post" action="edit.php" name="user_edit_form_email">
                                    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-user fa-1x" id="basic-addon4"></span>
                                    </div>
                                    <input type="text" id="currentEmail" class="form-control" value="<?php echo $_SESSION['user_email']; ?>" aria-label="CurrentEmail" aria-describedby="basic-addon4" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-lock fa-1x" id="basic-addon5"></span>
                                    </div>
                                    <input type="text" id="newEmail" type="email" name="user_email" class="form-control" placeholder="New Email" aria-label="New Email" aria-describedby="basic-addon5" required>

                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="v-pills-changePhone" role="tabpanel" aria-labelledby="v-pills-phone-tab">
                            <font color=red>CAUTION: Changing phone will require revalidation before you can access your account !!</font>
                                <form method="post" action="edit.php" name="user_edit_form_phone">
                                    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-user fa-1x" id="basic-addon4"></span>
                                    </div>
                                    <input type="text" id="currentPhone" class="form-control" value="<?php echo $_SESSION['user_phone']; ?>" aria-label="CurrentPhone" aria-describedby="basic-addon4" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-lock fa-1x" id="basic-addon5"></span>
                                    </div>
                                    <input type="text" id="newPhone" type="phone" name="user_phone" class="form-control" placeholder="New Phone" aria-label="New Phone" aria-describedby="basic-addon5" required>

                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="v-pills-changePassword" role="tabpanel" aria-labelledby="v-pills-password-tab">
                            <font color=red>CAUTION: Changing password will require you to re-register your devices !!</font>
                                   <br/>
                            
                                <form method="post" action="edit.php" name="user_edit_form_password">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fa fa-user fa-1x" id="basic-addon7"></span>
                                        </div>
                                        <input id="user_password_old" type="password" class="form-control" placeholder="Current Password" aria-label="Username" aria-describedby="basic-addon7" name="user_password_old" autocomplete="off">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fa fa-lock fa-1x" id="basic-addon8"></span>
                                        </div>
                                        <input id="user_password_new" type="password" class="form-control" placeholder="New Password" aria-label="Recipient's username" aria-describedby="basic-addon8"  name="user_password_new" autocomplete="off">

                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fa fa-lock fa-1x" id="basic-addon9"></span>
                                        </div>
                                        <input id="user_password_repeat" type="password" class="form-control" placeholder="Confirm Password" aria-label="Recipient's username" aria-describedby="basic-addon9" name="user_password_repeat" autocomplete="off">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('_footer.php'); ?>
