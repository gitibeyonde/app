<?php

require_once (__ROOT__ .'/classes/Utils.php');

function getButton($button, $ip, $uuid, $device_name, $port, $user_id, $user_name, $box, $timezone, $token, $role, $local, $tab){
    $action_name = $button[0];
    $action_message = $button[1];
    $action_popup = $button[3];
    $server_name=$_SERVER['SERVER_NAME'];
    //error_log("$action_message, $action_icon, $action_popup, $server_name");
    if ($action_popup != 'None') {
        return <<<HTMLMODAL
    <form name="{$action_name}" method=GET action="https://$ip/udp/device_action.php" target="_self">
        <input type=hidden name=server value="$server_name" />
        <input type=hidden name=view value="settings_dash" />
        <input type=hidden name=action value="$action_name" />
          <input type=hidden name=uuid value="$uuid" /> 
          <input type=hidden name=device_name value="$device_name" /> 
          <input type=hidden name=port value="$port" />
          <input  type=hidden name=user_id value="$user_id" />
          <input type=hidden name=user_name value="$user_name" />
          <input type=hidden name=box value="$box" />
          <input type=hidden name=timezone value="$timezone" />
          <input type="hidden" name="tk" value="$token" /> 
          <input type="hidden" name="role" value="$role" /> 
          <input type=hidden name=local value="$local" /> 
          <input type=hidden name=loc value="settings_dash" /> 
          <input type=hidden name=tab value="$tab" /> 
            <button type="button" data-toggle="modal" data-target="#{$action_name}_modal" class="btn btn-default btn-sm btn-block" name="$action_name" value="$action_name">
                <span>&nbsp;$action_message</span>
            </button>
              <div class="modal fade" tabindex="-1" role="dialog" id="{$action_name}_modal" aria-labelledby="{$action_name}_modal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">$action_message</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>$action_popup</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">$action_name</button>
                        </div>
                    </div>
                </div>
            </div>
     </form>
HTMLMODAL;
    }
    else {
        return <<<HTMLNONMODAL
                <form name="{$action_name}" method=GET action="https://$ip/udp/device_action.php" target="_self">
                <input type=hidden name=server value="$server_name" />
                    <input type=hidden name=view value="settings_dash" />
                    <input type=hidden name=action value="$action_name" />
                      <input type=hidden name=uuid value="$uuid" /> 
                      <input type=hidden name=device_name value="$device_name" /> 
                      <input type=hidden name=port value="$port" />
                      <input  type=hidden name=user_id value="$user_id" />
                      <input type=hidden name=user_name value="$user_name" />
                      <input type=hidden name=box value="$box" />
                      <input type=hidden name=timezone value="$timezone" />
                      <input type="hidden" name="tk" value="$token" /> 
                      <input type="hidden" name="role" value="$role" /> 
                      <input type=hidden name=local value="$local" /> 
                      <input type=hidden name=loc value="settings_dash" /> 
                     <input type=hidden name=tab value="$tab" /> 
                	<button type="submit" class="btn btn-default btn-sm btn-block" name="$action_name" value="$action_name">
                        <span>&nbsp;$action_message</span>
                    </button>
                </form>

HTMLNONMODAL;
    }
}


function getGPRSButton($button, $ip, $uuid, $device_name, $phone, $user_id, $user_name, $box, $timezone, $token, $role, $local, $tab){
    $action_name = $button[0];
    $action_message = $button[1];
    $action_popup = $button[3];
    $server_name=$_SERVER['SERVER_NAME'];
    //error_log("$action_message, $action_icon, $action_popup, $server_name");
    if ($action_popup != 'None') {
        return <<<HTMLMODAL2
    <form name="{$action_name}" method=GET action="/views/sms/web/sms_action.php" target="_self">
        <input type=hidden name=server value="$server_name" />
        <input type=hidden name=view value="settings_dash" />
        <input type=hidden name=action value="$action_name" />
          <input type=hidden name=uuid value="$uuid" />
          <input type=hidden name=device_name value="$device_name" />
          <input type=hidden name=phone value="$phone" />
          <input  type=hidden name=user_id value="$user_id" />
          <input type=hidden name=user_name value="$user_name" />
          <input type=hidden name=box value="$box" />
          <input type=hidden name=timezone value="$timezone" />
          <input type="hidden" name="tk" value="$token" />
          <input type="hidden" name="role" value="$role" />
          <input type=hidden name=local value="$local" />
          <input type=hidden name=loc value="settings_dash" />
          <input type=hidden name=tab value="$tab" />
            <button type="button" data-toggle="modal" data-target="#{$action_name}_modal" class="btn btn-default btn-sm btn-block" name="$action_name" value="$action_name">
                <span>&nbsp;$action_message</span>
            </button>
              <div class="modal fade" tabindex="-1" role="dialog" id="{$action_name}_modal" aria-labelledby="{$action_name}_modal">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">$action_message</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>$action_popup</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">$action_name</button>
                        </div>
                    </div>
                </div>
            </div>
     </form>
HTMLMODAL2;
    }
    else {
        return <<<HTMLNONMODAL2
                <form name="{$action_name}" method=GET action=/views/sms/web/sms_action.php" target="_self">
                <input type=hidden name=server value="$server_name" />
                    <input type=hidden name=view value="settings_dash" />
                    <input type=hidden name=action value="$action_name" />
                      <input type=hidden name=uuid value="$uuid" />
                      <input type=hidden name=device_name value="$device_name" />
                      <input type=hidden name=phone value="$phone" />
                      <input  type=hidden name=user_id value="$user_id" />
                      <input type=hidden name=user_name value="$user_name" />
                      <input type=hidden name=box value="$box" />
                      <input type=hidden name=timezone value="$timezone" />
                      <input type="hidden" name="tk" value="$token" />
                      <input type="hidden" name="role" value="$role" />
                      <input type=hidden name=local value="$local" />
                      <input type=hidden name=loc value="settings_dash" />
                     <input type=hidden name=tab value="$tab" />
                	<button type="submit" class="btn btn-default btn-sm btn-block" name="$action_name" value="$action_name">
                        <span>&nbsp;$action_message</span>
                    </button>
                </form>
                
HTMLNONMODAL2;
    }
}
?>