<style>
    #alert_placeholder_motion {
        position: fixed;
        width: 40%;
        right: 0;
        height: 20px;
        z-index: 999;
        opacity: 0.7;
    }
</style>

<script src="js/master.js"></script>
<script>
    $(document).ready(function(){
       var message = getUrlParameter('message');
        console.log(message);
        
        if(message !== "" && message !== undefined){
            showalert(message, "alert-success");
        }
        
        function showalert(message,alerttype) {
    $('#alert_placeholder_motion').append('<div id="alertdiv" class="alert ' +  alerttype + '"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')

    setTimeout(function() { 
      $("#alertdiv").remove();

    }, 5000);
  }
        
    });
    
</script>



                        <b>Motion Capture Control</b>
                           <div id = "alert_placeholder_motion"></div>
                                    <div class="card bg-faded">
                                     <div class="row">
                                      <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Grid Detect : <?php echo $settings['grid_detect']; ?></font>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Face Detect : <?php echo $settings['face_detect']; ?></font>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Motion Quality : <?php echo $settings['motion_quality']; ?></font>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Face Min : <?php echo $settings['face_min']; ?></font>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Motion Tolerance : <?php echo $settings['motion_tolerance']; ?></font>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Capture Delta : <?php echo $settings['capturedelta']; ?></font>
                                        </div>
                                     </div>
                                     </div>
                                     
                                     <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->incmotionq, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                         </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->decmotionq, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->incfacemin, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->decfacemin, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                        <?php if ($settings['face_detect'] == 1) { ?>
                                            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                                <?php echo getButton($utils->dsfcdtct, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                          </div>
                                        <?php } else { ?>
                                          <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                                <?php echo getButton($utils->enfcdtct, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                          </div>
                                        <?php } ?>
                                       <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->inctol, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                       </div>
                                       <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->dectol, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                       </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->incmdelta, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                      </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->decmdelta, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                      </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->engrd, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                      </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->dsgrd, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                      </div>
                                   </div>
                                   
                                    <br/>
                                   
                                    <b>Snapshots</b>
                                    <div class="well">
                                    
                                     <div class="card bg-faded">
                                     <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Hourly Snapshot : <?php echo $settings['hourly_snapshot']; ?></font>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <font size=2>Snapshot Quality : <?php echo $settings['snap_quality']; ?></font>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="row">
                                     <?php if ($settings['hourly_snapshot'] == 1) { ?>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->dishrsnap, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                         </div>
                                      <?php } else { ?>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->enbhrsnap, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                      <?php } ?>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->incsnapq, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->decsnapq, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                                            <?php echo getButton($utils->recordtoggle, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "motion"); ?>
                                        </div>
                                   </div>
                         
<?php include('common/add_space.php'); ?>      