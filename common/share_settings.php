                       
                                        <h3>Sharing lets you share motion feed</h3><hr/>
                                    
                                         <div class="row">
                                            <div class="col-sm-12 col-md-8"> 
                                               <?php if (strlen($share_name) > 1 ) { 
                                                    echo '<br/><p> Share is https://app.ibeyonde.com/share.php?animate=true&id='.base64_encode($share_name."@".$uuid).'<br/><br/>';
                                                    echo '<br/><i>Share the above url to share the correspnding feed with your friends or on your website. You can change it anytime.</i>';
                                                }
                                                else {
                                                    echo 'Not shared.<br/>'; 
                                                    echo '</br><i>To start sharing fill in a share password below. Share password should have only characters and digits.</i>';
                                                }?>
                                                <br/>
                                                <form class="form-horizontal" name=shareMotion method=GET action="sql_action.php">
                                                    <label> Share Name : </label><input type="text" name="share_name" value="<?php echo $share_name; ?>" style="width: 6em; height: 2em;"/> 
                                                    <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                                                    <input type=hidden name=view value="<?php echo SETTINGS_DASH ?>" /> 
                                                    <input type=hidden name=action value="ShareMotion" /> 
                                                    <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />  
                                                    <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                                                    <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                    <input type=hidden name=user_email value="<?php echo $user_email; ?>"
                                                     <input type=hidden name=loc value="settings_dash" /> 
                                                    <input type=hidden name=tab value="sharing" />  />
                                                    <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="ShareMotion">
                                                       Share Motion </button>
                                                </form>
                                             </div> 
                                             <div class="col-sm-12 col-md-4"> 
                                             <div class="embed-responsive embed-responsive-4by3">
                                                 <iframe class="embed-responsive-item" scrolling="no" frameborder="0" id="<?php echo $device->uuid; ?>0" style="display: block;"
                                                    src="../views/motion.php?&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=true"> </iframe>
                                             </div>
                                             </div>     
                                        </div>
                                        
                                        
                                        <div class="row">
                                            <br /> <br />
                                        </div>
                                       
                                        
                                        <h3>Sharing lets you share your devices with another user</h3>
                                        <p> The user with who you want to share your device should be a registered user. </p><hr/>
                                         <div class="row">
                                            <div class="col-sm-12 col-md-12"> 
                                                <form class="form-horizontal" name=shareDevice method=GET action="sql_action.php">
                                                    <label> User Name : </label><input type=text name=share_user_name value="" />
                                                    <label>Select Devices: </label>
                                                    <?php foreach ( $devices as $device ) { ?>
                                                      <label class="radio-inline">&nbsp;&nbsp;&nbsp;<input name="<?php echo $device->uuid; ?>" type="radio" value="1"><?php echo $device->device_name; ?></label>
                                                    <?php } ?>
                                                    <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                                                    <input type=hidden name=action value="ShareDevice" />  
                                                    <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                                                    <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />  
                                                    <input type=hidden name=view value="<?php echo SETTINGS_DASH ?>" /> 
                                                    <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                    <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                                     <input type=hidden name=loc value="settings_dash" /> 
                                                    <input type=hidden name=tab value="sharing" /> 
                                                    <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="ShareDevice">
                                                       Share Device </button>
                                                </form>
                                            
                                            </div>
                                        </div>
                                        
<?php include('common/add_space.php'); ?>