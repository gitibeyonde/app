<?php
include(__ROOT__.'/views/_header.php');

require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
include_once(__ROOT__ . '/classes/wf/data/WfAudience.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');


$log = $_SESSION['log'] = new Log ("info");
$user_id = $_SESSION['user_id'];

$smsmessage = new SmsMessage();

error_log("Message Post is = " . isset($_POST['submit']));
$action = null;
$message_id = null;
if (isset ( $_POST ['submit'] )) {
    $action = $_POST ['submit'];
    error_log ( "Action is " . $action );
    if ($action == "add-sms") {
        $name = $_POST ['name'];
        $template = $_POST ['template'];
        $response_type = $_POST ['response_type'];
        error_log ( $template . $response_type );
        $smsmessage->createMessage ( $user_id, $name, $template, $response_type );
    } else if ($action == "edit-sms") {
        $message_id = $_POST ['message_id'];
        error_log ( "messaging id is=" . $message_id );
    } else if ($action == "save-sms") {
        $message_id = $_POST ['message_id'];
        $template = $_POST ['template'];
        $smsmessage->updateMessage ( $user_id, $message_id, $template );
        error_log ( "messaging id is=" . $message_id );
    } else if ($action == "delete-sms") {
        $message_id = $_POST ['message_id'];
        $smsmessage->deleteMessage($user_id, $message_id);
    } 
}
$smsmessages = $smsmessage->getMessage ( $user_id );
?>   

 <script>
      function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
          val.value = val.value.substring(0, 160);
        } else {
          $('#charNum').text(160 - len);
        }
      };
    </script> 
    
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>
      <br/>
      <h3>Goto</h3>
      <br/>
       <div class="row">
        <div class="col-lg-4 col-md-4">
            <form class="form-inline" action="/index.php"  method="get">
            <input type=hidden name=view value="<?php echo MESSAGE_TEMPLATE_SMS; ?>">
            <button type="submit" name="submit" value="template_sms" style="background: transparent; border: 0;">
            <h4 style='text-decoration: underline;'>SMS Template</h4></button>
            </form>
        </div>
        <div class="col-lg-4 col-md-4">
            <form action="/index.php"  method="get">
            <input type=hidden name=view value="<?php echo MESSAGE_TEMPLATE_EMAIL; ?>">
            <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
            <h4>EMAIL Template</h4></button>
            </form>
        </div>
      
     </div> 
      <br/>
      <h3>Create SMS Message Templates</h3>
      <form action="/index.php?view=<?php echo MESSAGE_TEMPLATE_SMS; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group row flex-v-center">
        <div class="col-xs-3 col-sm-2">
          <label for="name">Name:</label>
        </div>
        <div class="col-xs-3">
          <input type="text" class="form-control" id="name" placeholder="Message name" name="name" required>
          <div class="valid-feedback">Valid.</div>
          <div class="invalid-feedback">Please fill out this field.</div>
         </div>
       </div>
        
       <div class="form-group row flex-v-center">
        <div class="col-xs-3 col-sm-2">
          <label for="desc">SMS Template</label>
        </div>
         <div class="col-xs-3">
            <textarea class="form-control" rows="5" name="template" onkeyup="countChar(this)" placeholder="Enter Sms Template" required></textarea>
           <div id="charNum"></div>
          <div class="valid-feedback">Valid.</div>
          <div class="invalid-feedback">Please fill out this field.</div>
         </div>
         </div>
         
       <div class="form-group row flex-v-center">
         <label for="response_type">Response type:&nbsp;:&nbsp;:&nbsp;</label>
         
         <div class="form-check-inline">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="response_type" value="None" checked>None
          </label>
        </div>
        
         <div class="form-check-inline">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="response_type" value="yes-no">yes-no
          </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="response_type" value="datetime">datetime
          </label>
        </div>
        <div class="form-check-inline disabled">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="response_type" value="opinion">opinion
          </label>
        </div>
      </div>
      <button type="submit" name="submit" value="add-sms" class="btn btn-sim1">Add</button>
    </form>
    
    <hr/>
    <br/>
	<h3>SMS Message Listing</h3>
    <table class="table table-striped table-hover">
        <?php
        if ($smsmessages == null || count($smsmessages) == 0) {
            echo "<tr><td colspan=5><font style='color: #3862c6;'>No Template found, create one !</font></td></tr>";
        } else {
       ?> 
       <tr>
       <th>Name</th>
       <th>Template</th>
       <th>Response</th>
       <th>Edit</th>
       <th>Bin</th>
      </tr>
       <?php    foreach ($smsmessages as $map) { 
           if ($action == "edit-sms" && $message_id == $map['id']){
           ?>
        <form class="form-inline" action="/index.php?view=<?php echo MESSAGE_TEMPLATE_SMS; ?>"  method="post">
            <tr>
            <td><?php echo $map['name']; ?></td>
            <td><input type=text name=template value='<?php echo $map['template']; ?>' size=60></td>
            <td><?php echo $map['response']; ?></td>
            <td><?php echo $map['endDate']; ?></td>
              <td width="20px">
                <input type=hidden name=message_id value=<?php echo $map['id']; ?>>
                <button name="submit" type="submit" value="save" class="btn btn-sim1">
                    Check</button>
              </td>
              <td width="20px">
              </td>
            </tr>
        </form>
        <?php } else { ?>
        <tr>
        <td><?php echo $map['name']; ?></td>
        <td><?php echo $map['template']; ?></td>
        <td><?php echo $map['response']; ?></td>
          <td width="20px">
            <form class="form-inline" action="/index.php?view=<?php echo MESSAGE_TEMPLATE_SMS; ?>"  method="post">
            <input type=hidden name=message_id value=<?php echo $map['id']; ?>>
            <button name="submit" type="submit" value="edit-sms" class="btn btn-sim1">
                    Edit</button>
            </form>
          </td>
          <td width="20px">
            <form class="form-inline" action="/index.php?view=<?php echo MESSAGE_TEMPLATE_SMS; ?>"  method="post">
            <input type=hidden name=message_id value=<?php echo $map['id']; ?>>
            <button name="submit" type="submit" value="delete-sms"  class="btn btn-sim1"">
                    Delete</button>
            </form>
          </td>
        </tr>
    <?php } } }?>
    </table>
  
</div>
<?php include(__ROOT__.'/views/_footer.php'); ?>
 </body>
  
