
<?php if ($_SESSION['role'] == 'USER') {
    echo "<br/><font color=red> Please, subscribe to get access to Alerts </font>";
    return;
}?>

<div class="container">

            <div class=row>
               <p>
                                	<b>Unusual Events</b>
                                	<form name="alert_config" method="get" action="sql_action.php">
                                        <?php if ($settings['grid_detect'] == 1) { ?>
                                            <p> <b> Set motion sensitive grid</b> Mark the areas where any motion will generate an alert. </p>
                                               <div class="checkbox">
                                               <table background="<?php echo $furl; ?>" style="background-repeat:no-repeat;" width=450 height=340>
                                                 <tr><td>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r1" type="checkbox" value="1" <?php echo ($cbits[0] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r2" type="checkbox" value="1" <?php echo ($cbits[1] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r3" type="checkbox" value="1" <?php echo ($cbits[2] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r4"  type="checkbox" value="1" <?php echo ($cbits[3] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r5"  type="checkbox" value="1" <?php echo ($cbits[4] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                 </tr></td>
                                                 <tr><td>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r6"  type="checkbox" value="1" <?php echo ($cbits[5] == 1 ? "checked" : ""); ?>">&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r7"  type="checkbox" value="1" <?php echo ($cbits[6] == 1 ? "checked" : ""); ?>">&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r8"  type="checkbox" value="1" <?php echo ($cbits[7] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r9"  type="checkbox" value="1" <?php echo ($cbits[8] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r10"  type="checkbox" value="1" <?php echo ($cbits[9] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;10&nbsp;&nbsp;&nbsp;</label>
                                                 </tr></td>
                                                 <tr><td>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r11"  type="checkbox" value="1" <?php echo ($cbits[10] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;11&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r12"  type="checkbox" value="1" <?php echo ($cbits[11] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;12&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r13"  type="checkbox" value="1" <?php echo ($cbits[12] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;13&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r14"  type="checkbox" value="1" <?php echo ($cbits[13] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;14&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r15"  type="checkbox" value="1" <?php echo ($cbits[14] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;15&nbsp;&nbsp;&nbsp;</label>
                                                 </tr></td>
                                                 <tr><td>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r16"  type="checkbox" value="1" <?php echo ($cbits[15] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;16&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r17"  type="checkbox" value="1" <?php echo ($cbits[16] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;17&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r18"  type="checkbox" value="1" <?php echo ($cbits[17] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;18&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r19"  type="checkbox" value="1" <?php echo ($cbits[18] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;19&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r20"  type="checkbox" value="1" <?php echo ($cbits[19] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;20&nbsp;&nbsp;&nbsp;</label>
                                                 </tr></td>
                                                 <tr><td>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r21"  type="checkbox" value="1" <?php echo ($cbits[20] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;21&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r22"  type="checkbox" value="1" <?php echo ($cbits[21] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;22&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r23"  type="checkbox" value="1" <?php echo ($cbits[22] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;23&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r24"  type="checkbox" value="1" <?php echo ($cbits[23] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;24&nbsp;&nbsp;&nbsp;</label>
                                                   <label>&nbsp;&nbsp;&nbsp;<input name="r25"  type="checkbox" value="1" <?php echo ($cbits[24] == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;&nbsp;25&nbsp;&nbsp;&nbsp;</label>
                                                 </tr></td>
                                                 <tr><td>
                                                 <br/>
                                                 </tr></td>
                                               </table>
                                               </div>
                                         <?php }
                                            else {
                                                echo "<p>For grid alerts, enable grid in Device Setup </p><br/>";
                                            }?>

                                          <!--  <div class="checkbox">
                                              <label><input type="checkbox" name="unusual"  value="1" <?php echo ($dev_alert_config->unusual == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;Detect unusual events</label> (Anything unusual will be reported.)
                                          </div>
                                           -->

                                          <b>Device Health</b>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="ping_cb"  value="0" min="60" max="1440" <?php echo ($dev_alert_config->ping >= 60 ? "checked" : ""); ?>>&nbsp;&nbsp;Device offline for more than 60 minutes</label>
                                               <input type="number" name="ping" value="<?php echo $dev_alert_config->ping; ?>"  style="width: 3em"> Minutes.
                                            </div>


                                          <b>Motion</b>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="no_motion_hour_cb" value="1"  <?php echo ($dev_alert_config->no_motion_hour == -1 ? "" : "checked"); ?>>&nbsp;&nbsp;No motion for last </label>
                                                  <input type="number" name="no_motion_hour" value="<?php echo $dev_alert_config->no_motion_hour; ?>" style="width: 3em"> hours.</input>
                                                </div>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="all_motion" value="1" <?php echo ($dev_alert_config->all_motion == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;All motion alerts.</label>
                                                </div>

                                                 <div class="checkbox">
                                                  <label>
                                                  <select name="people_count">
                                                  <option value="0"  <?php echo ($dev_alert_config->people_count == 1 ? "selected" : ""); ?>>0</option>
                                                  <option value="1"  <?php echo ($dev_alert_config->people_count == 1 ? "selected" : ""); ?>>1</option>
                                                  <option value="2"  <?php echo ($dev_alert_config->people_count == 2 ? "selected" : ""); ?>>2</option>
                                                  <option value="3"  <?php echo ($dev_alert_config->people_count == 3 ? "selected" : ""); ?>>3</option>
                                                  <option value="4"  <?php echo ($dev_alert_config->people_count == 4 ? "selected" : ""); ?>>4</option>
                                                  <option value="5"  <?php echo ($dev_alert_config->people_count == 5 ? "selected" : ""); ?>>5</option>
                                                  </select>
                                                  &nbsp;&nbsp;People count.</label>
                                                </div>

                                                 <div class="checkbox">
                                                  <label><input type="checkbox" name="classify" value="1" <?php echo ($dev_alert_config->classify == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;Classify Artefacts.</label>
                                                <label><input type="checkbox" name="sub_category[]" value = "9" <?php echo (in_array(9,$categories) ? "checked" : ""); ?>  > General instruments </label>
                                                <label><input type="checkbox" name="sub_category[]" value = "18" <?php echo (in_array(18,$categories) ? "checked" : ""); ?>> weapons </label>
                                                <label><input type="checkbox" name="sub_category[]" value = "20"<?php echo (in_array(20,$categories) ? "checked" : ""); ?>> cloth </label>
                                                <label><input type="checkbox" name="sub_category[]" value = "25"<?php echo (in_array(25,$categories) ? "checked" : ""); ?>> animals </label>
                                                </div>




                                        <?php if ($settings['face_detect'] == 1) { ?>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="unrecog" value="1"  <?php echo ($dev_alert_config->unrecog == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;Face detected but not recognized.</label>
                                                </div>


                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="recog" value="1"  <?php echo ($dev_alert_config->recog == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;Face detected and recognized.</label>
                                                </div>
                                        <?php }
                                        else {
                                            echo "For face alerts, enable face detection in Device Setup <br/>";

                                        }?>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="license" value="1"  <?php echo ($dev_alert_config->license == 1 ? "checked" : ""); ?>>&nbsp;&nbsp;License plate.</label>
                                                </div>

                                        <?php if (strpos($device->capabilities, "TEMPERATURE") !== false) { ?>
                                          <b>Temperature</b>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="temp_high_cb" value="1"  min="-99" max="999" <?php echo ($dev_alert_config->temp_high < 999 ? "checked" : ""); ?>>&nbsp;&nbsp;Temperature goes above </label>
                                                  <input type="number" name="temp_high" value="<?php echo $dev_alert_config->temp_high; ?>"  style="width: 3em"> Degree Centigrade.
                                                </div>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="temp_low_cb" value="1"  min="-99" max="999" <?php echo ($dev_alert_config->temp_low > -99 ? "checked" : ""); ?>>&nbsp;&nbsp;Temperature goes below </label>
                                                  <input type="number" name="temp_low" value="<?php echo $dev_alert_config->temp_low; ?>"  style="width: 3em"> Degree Centigrade.
                                                </div>

                                          <b>Humidity</b>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="humid_high_cb"  min="0" max="100" value="1" <?php echo ($dev_alert_config->humid_high < 999 ? "checked" : ""); ?>>&nbsp;&nbsp;Humidity goes above </label>
                                                  <input name="humid_high" type="number" value="<?php echo $dev_alert_config->humid_high; ?>"  style="width: 3em"> Units.
                                                </div>

                                                <div class="checkbox">
                                                  <label><input type="checkbox" name="humid_low_cb" min="0" max="100" value="1" <?php echo ($dev_alert_config->humid_low > -99 ? "checked" : ""); ?>>&nbsp;&nbsp;Humidity goes below </label>
                                                  <input name="humid_low" type="number" value="<?php echo $dev_alert_config->humid_low; ?>"  style="width: 3em"> Units.
                                                </div>
                                         <?php } ?>

                                          <b>Enable email for</b>
                                           <div class="checkbox">
                                             <label><input name="m1" type="checkbox" value="1" <?php echo ($mbits[0] == 1 ? "checked" : ""); ?>>Face Recognition</label>
                                             <label><input name="m2" type="checkbox" value="1" <?php echo ($mbits[1] == 1 ? "checked" : ""); ?>>Face Detection</label>
                                             <label><input name="m3" type="checkbox" value="1" <?php echo ($mbits[2] == 1 ? "checked" : ""); ?>>Grid Detected</label>
                                             <label><input name="m4" type="checkbox" value="1" <?php echo ($mbits[3] == 1 ? "checked" : ""); ?>>Motion</label>
                                             <label><input name="m5" type="checkbox" value="1" <?php echo ($mbits[4] == 1 ? "checked" : ""); ?>>High Temperature</label>
                                             <label><input name="m6" type="checkbox" value="1" <?php echo ($mbits[5] == 1 ? "checked" : ""); ?>>Low Temperature</label>
                                             <label><input name="m7" type="checkbox" value="1" <?php echo ($mbits[6] == 1 ? "checked" : ""); ?>>High Humidity</label>
                                             <label><input name="m8" type="checkbox" value="1" <?php echo ($mbits[7] == 1 ? "checked" : ""); ?>>Low Humidity</label>
                                             <label><input name="m9" type="checkbox" value="1" <?php echo ($mbits[8] == 1 ? "checked" : ""); ?>>License Plate</label>
                                           </div>


                                          <b>Enable Push Notification for</b>
                                           <div class="checkbox">
                                             <label><input name="p1" type="checkbox" value="1" <?php echo ($pbits[0] == 1 ? "checked" : ""); ?>>Face Recognition</label>
                                             <label><input name="p2" type="checkbox" value="1" <?php echo ($pbits[1] == 1 ? "checked" : ""); ?>>Face Detection</label>
                                             <label><input name="p3" type="checkbox" value="1" <?php echo ($pbits[2] == 1 ? "checked" : ""); ?>>Grid Detected</label>
                                             <label><input name="p4" type="checkbox" value="1" <?php echo ($pbits[3] == 1 ? "checked" : ""); ?>>Motion</label>
                                             <label><input name="p5" type="checkbox" value="1" <?php echo ($pbits[4] == 1 ? "checked" : ""); ?>>High Temperature</label>
                                             <label><input name="p6" type="checkbox" value="1" <?php echo ($pbits[5] == 1 ? "checked" : ""); ?>>Low Temperature</label>
                                             <label><input name="p7" type="checkbox" value="1" <?php echo ($pbits[6] == 1 ? "checked" : ""); ?>>High Humidity</label>
                                             <label><input name="p8" type="checkbox" value="1" <?php echo ($pbits[7] == 1 ? "checked" : ""); ?>>Low Humidity</label>
                                             <label><input name="p9" type="checkbox" value="1" <?php echo ($pbits[8] == 1 ? "checked" : ""); ?>>License Plate</label>
                                           </div>

                                            <b>Disable Repeated Alerts</b>

                                            <div class="checkbox">
                                              <label><input type="checkbox" name="no_repeat" value="1" <?php echo ($dev_alert_config->no_repeat_delta > 0 ? "checked" : ""); ?>>&nbsp;&nbsp;Disable Repeated Alerts for </label>
                                              <input name="no_repeat_delta" type="number" min="0" max="86400" value="<?php echo $dev_alert_config->no_repeat_delta; ?>"  style="width: 6em"> Minutes.
                                            </div>

                                            <br/>
                                            <input type=hidden name=view value="<?php echo SETTINGS_DASH ?>" />
                                            <input type=hidden name=action value="AlertConfig" />
                                            <input type=hidden name=uuid value="<?php echo $uuid; ?>" />
                                            <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                                            <input type="hidden" name="timezone" value="<?php echo $timezone; ?>"/>
                                            <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                            <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                                            <input type=hidden name=loc value="settings_dash" />
                                            <input type=hidden name=tab value="alert" />
                                            <button class="btn btn-success"  style="position: center;background-color: #c1c19c;"  type="submit" type=submit name="submit" value="Update Alert Config" >
                                            <span>Update Alert Config</span>
                                            </button>

                                          </form>
                                 </p>
            </div>

</div>