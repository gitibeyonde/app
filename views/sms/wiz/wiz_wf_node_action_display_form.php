<?php
include_once(__ROOT__ . '/classes/sms/SmsIntent.php');

$Int = new SmsIntent();
$Lintents = $Int->getIntentsN();

?>
  <div class="container">
      <label><h3>Select Action</h3>&emsp;&emsp;&emsp;&emsp;</label>

      <input class="from-inline" id="cbunmatched<?php echo $count; ?>"  type="radio" name="action_name[<?php echo $count; ?>]" value="unmatched">
             <label><h4>Next</h4></label>
      <input class="from-inline" id="cbsearch<?php echo $count; ?>"  type="radio" name="action_name[<?php echo $count; ?>]" value="search">
             <label><h4>Button</h4></label>
      <input class="from-inline" id="cbchoice<?php echo $count; ?>"  type="radio" name="action_name[<?php echo $count; ?>]" value="choices">
            <label><h4>Choice</h4></label>
      <input class="from-inline" id="cbextract<?php echo $count; ?>"  type="radio" name="action_name[<?php echo $count; ?>]" value="extract">
             <label><h4>Input</h4></label>
      <input class="from-inline" id="cbupload<?php echo $count; ?>"  type="radio"  name="action_name[<?php echo $count; ?>]" value="upload">
             <label><h4>Upload</h4></label>
  </div>


   <div id="intent<?php echo $count; ?>" style="display: none">
     <label><h3>Select Intent</h3></label>
        <select name="action_desc[<?php echo $count; ?>]">
        <?php foreach($Lintents as $int) { ?>
          <option value="<?php echo $int['intent']; ?>"><?php echo $int['intent'];?></option>
        <?php } ?>
        </select>
    </div>

    <div class="form-control" id="desc<?php echo $count; ?>" style="display: none">
     <label><h3>Name</h3></label>
        <input id="input_desc<?php echo $count; ?>" type="text" Placeholder="button name or comma seprated list for choices or KB pattern from right" name="action_desc[<?php echo $count; ?>]" value="">
    </div>

    <div class="form-control" id="extract<?php echo $count; ?>"  style="display: none">
     <label><h3>Input Name and Type Pattern</h3></label>
        <input id="input_extract<?php echo $count; ?>" type="text" Placeholder="Select from define input on the right"  name="choice_extract[<?php echo $count; ?>]" value="">
    </div>

    <div id="nextstate<?php echo $count; ?>">
     <label><h3>Next Page</h3></label>
        <input type="text" name="next_state[<?php echo $count; ?>]" Placeholder="Select Next Page" list="next_state">
        <datalist id="next_state">
        <?php
            $nodes = $WFDB->getNodes($bot_id);
            foreach ($nodes as $node){
                echo "<option>".$node['state']. "</option>";
            }
            ?>
        </datalist>
  </div>

 <script>
 $("#cbunmatched<?php echo $count; ?>").click(function(){
     console.log("Intent clicked");
     $("#desc<?php echo $count; ?>").hide();
     $("#extract<?php echo $count; ?>").hide();
     $("#action_kb_tool").hide();
     $("#action_extract_tool").hide();
 });
 $("#cbsearch<?php echo $count; ?>").click(function(){
     console.log("Search clicked");
     $("#desc<?php echo $count; ?>").show();
     $("#desc<?php echo $count; ?> h3").text("Button Name");
     $("#action_kb_tool").hide();
     $("#action_extract_tool").hide();
     $("#input_desc<?php echo $count; ?>").removeAttr("Placeholder");
     $("#input_desc<?php echo $count; ?>").attr("Placeholder", "Button Name");
 });
 $("#cbchoice<?php echo $count; ?>").click(function(){
     console.log("Choice clicked");
     $("#desc<?php echo $count; ?>").show();
     $("#extract<?php echo $count; ?>").show();
     $("#desc<?php echo $count; ?> h3").text("Choice List/KB Pattern");
     $("#extract<?php echo $count; ?> h3").text("Input Name and Type Pattern");
     $("#action_kb_tool").show();
     $("#action_extract_tool").show();
     $("#input_desc<?php echo $count; ?>").removeAttr("Placeholder");
     $("#input_desc<?php echo $count; ?>").attr("Placeholder", "Comma separated list of choices or pattern selected from KB tool on right");
});
 $("#cbextract<?php echo $count; ?>").click(function(){
     console.log("Extract clicked");
     $("#action_extract_tool").show();
     $("#extract<?php echo $count; ?>").show();
     $("#extract<?php echo $count; ?> h2").text("Input Name and Type Pattern");
     $("#desc<?php echo $count; ?>").hide();
     $("#action_kb_tool").hide();
     $("#desc<?php echo $count; ?>").hide();
 });
 $("#cbupload<?php echo $count; ?>").click(function(){
     console.log("Upload clicked");
     $("#desc<?php echo $count; ?> h3").text("Upload Type - media or file");
     $("#desc<?php echo $count; ?>").show();
     $("#extract<?php echo $count; ?>").hide();
     $("#action_extract_tool").hide();
     $("#action_kb_tool").hide();
     $("#action_kb_tool").hide();
     $("#input_desc<?php echo $count; ?>").removeAttr("Placeholder");
 });
 </script>

