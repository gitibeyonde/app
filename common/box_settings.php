
    
                                        <h3>Boxing lets you organize your devices</h3><hr/>
                                        
                                        <br/>
                                        
                                        <b>Rename your device with id <?php echo $device->uuid; ?></b><hr/>
                                        
                                         <div class="row">
                                            <div class="col-sm-12 col-md-6"> 
                                                <form class="form-horizontal" name=changeDeviceName method=GET action="sql_action.php">
                                                    <label>Device Name: </label>
                                                    <input type="text" name="device_name" value="<?php echo $device_name; ?>" style="width: 6em; height: 2em;"/> 
                                                    <input type=hidden name=view value="<?php echo SETTINGS_DASH; ?>" /> 
                                                    <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />  
                                                    <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                                                    <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                    <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                                    <input type="hidden" name="tk" value="<?php echo $device->token;?>" /> 
                                                    <input type=hidden name=action value="ChangeDeviceName" /> 
                                                     <input type=hidden name=loc value="settings_dash" /> 
                                                    <input type=hidden name=tab value="boxing" /> 
                                                    <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="Change">
                                                       Change Name </button>
                                                </form>
                                            </div>
                                            
                                          </div>
                                          
                                            
                                        <div class="row">
                                            <br /> <br />
                                        </div>
                                      
                                           
                                      <div class="row">
                                        <div class="col-sm-12 col-md-6"> 
                                            <b>Move Device To Box</b><hr/>
                                             <table>
                                                <tr>
                                                    <td><b>Device is in <code><?php echo $device->box_name; ?></code> box.</b>
                                                    </td>
                                                    <td><b>&nbsp;&nbsp; Move to</td> 
                                                    <?php
                                                    foreach ( $boxes as $box ) {
                                                        if (strcmp ( $device->box_name, $box ) == 0) {
                                                            continue;
                                                        }
                                                        ?>
                                                        </b>
                                                    <td> 
                                                        <form class="form-horizontal" name=moveToBox method=GET action="sql_action.php">
                                                            <input type=hidden name=view value="<?php echo SETTINGS_DASH; ?>" /> 
                                                            <input type=hidden name=uuid  value="<?php echo $device->uuid ?>" /> 
                                                            <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> 
                                                            <input type="hidden" name="box" value="<?php echo $box;?>" />
                                                            <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                            <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                                            <input type="hidden" name="tk" value="<?php echo $device->token;?>" /> 
                                                            <input type=hidden name=action value="MoveToBox" /> 
                                                            <input type=hidden name=loc value="settings_dash" /> 
                                                             <input type=hidden name=tab value="boxing" /> 
                                                            <input class="btn btn-sm btn-link" type="submit" type=submit name="submit" value="<?php echo $box; ?>," />
                                                        </form>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                
                                            </table>
                                          </div>
                                        </div>
                                            
                                            
                                        <div class="row">
                                            <br /> <br />
                                        </div>
                                      
                                         <div class="row">
                                            <div class="col-sm-6 col-md-6"> 
                                            <b>Create New Box</b><hr/>
                                               <form class="form-horizontal" name=createBox method=GET action="sql_action.php">
                                                    <input type=hidden name=view value="<?php echo SETTINGS_DASH;  ?>" /> 
                                                    <input type="text" name="box" value="" style="width: 6em; height: 2em;"/> 
                                                    <input type=hidden name=uuid  value="<?php echo $device->uuid ?>" /> 
                                                    <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> 
                                                    <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                    <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                                    <input type="hidden" name="tk" value="<?php echo $device->token;?>" /> 
                                                    <input type=hidden name=action value="CreateBox" />
                                                    <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="CreateBox">
                                                     Create Box </button>
                                                </form>
                                             </div>
                                            <div class="col-sm-6 col-md-6"> 
                                             <b>Existing Boxes</b><hr/>
                                              <?php foreach ($boxes as $box){ ?>
                                                    <form class="form-horizontal" name=deleteBox method=GET action="sql_action.php">
                                                      <label style="width: 100px; display: block;"><?php echo $box; ?></label>
                                                       <input type=hidden name=view value="<?php echo SETTINGS_DASH;  ?>" /> 
                                                       <input type="hidden" name="box" value="<?php echo $box;?>" /> 
                                                        <input type=hidden name=uuid  value="<?php echo $device->uuid ?>" /> 
                                                        <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> 
                                                        <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                                        <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                                       <input type="hidden" name="tk" value="<?php echo $device->token;?>" /> 
                                                       <input type=hidden name=action value="DeleteBox" />
                                                        <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="DeleteBox">
                                                           Delete</button>
                                                    </form>
                                               <?php } ?>
                                         </div>
                                         </div>
                                         
<?php include('common/add_space.php'); ?>