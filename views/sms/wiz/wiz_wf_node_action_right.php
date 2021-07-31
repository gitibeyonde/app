

<div class="col-lg-3 col-md-3 sel1" >
 <div class="container">
    <div class="row">
        <h3  class="sel2"><?php echo $wf['name']; ?></h3>
    </div>
    <div class="row">
        <h4><?php echo $state; ?></h4>
    </div>

    <br/>
    <div id="action_kb_tool" style="display: none;">
    <?php $Lbkb = WfEdit::getBotKBStructure($user_id, $bot_id);
        if (count($Lbkb) <= 0 ){ ?>
         <div class="row">
            <h3 class="sel6">Empty KB</h3>
         </div>
   <?php } else { ?>

       <div class="row">
        <h3 class="sel2">KB Placeholder</h3>
       </div>

       <div class="row">
         <form id="from_bot_kb" action="#" method="get" onSubmit="return onSubmit();">
              <input type="hidden" id="viewname" name="view" value="workflow_node">
               <label for="schema">Schema:</label>
               <select id="schema" name="schema">
                   <option value="--">--</option>
                     <?php
                        foreach($Lbkb as $tn=>$col){
                         ?>
                        <option value="<?php echo $tn; ?>"><?php echo $tn; ?></option>
                     <?php } ?>
               </select>
               <label for="column">Column:</label>
               <select id="column" name="column">
                   <option value="--">--</option>
               </select>

               <div class="form-group">
                   <label for="condition"></label>
                   <input type=hidden id="condition" name="condition" value="" placeholder="Enter condition"></input>
                   <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                   <input type=hidden name=user_id value="<?php echo $user_id; ?>">
                  <button id="submit" type="submit" name="submit" value="addsubstitutekbs" class="btn btn-sim3">add</button>
              </div>
         </form>
       </div>
  <?php } ?>
  </div> <!-- end action_kb_tool  -->

    <div id="action_extract_tool" style="display: none;">
      <br/>
      <div class="row">
        <h3  class="sel2">Define Input</h3>
      </div>

        <div class="row">
          <form id="form_extract" action="#" method="get" onsubmit="return setExtract();">
                <div class="form-group">
                  <label for="type">Value Name:</label>
                 <input type=text id="extractname" name="name" placeholder="value name" required></input>
                </div>

                 <div class="form-group">
                    <label for="type">Value Type:</label>
                   <select id="extracttype" name="type" required>
                        <option value="name">name</option>
                        <option value="email">email</option>
                        <option value="phone">phone</option>
                        <option value="datetime">datetime</option>
                        <option value="date">date</option>
                        <option value="string">string</option>
                        <option value="number">number</option>
                        <option value="integer">integer</option>
                        <option value="decimal">decimal</option>
                   </select>
                  </div>


                 <div class="form-group">
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=user_id value="<?php echo $user_id; ?>">
                   <button type="submit" name="submit" value="addextract" class="btn btn-sim3" >add</button>
                </div>
          </form>
        </div>
    </div> <!-- end action_extract_tool -->
</div> <!--  end container -->
</div>
<script>
$('#form_extract').submit(function() {
    var result = "{{ex/" + $("#extractname").val() + "/" + $("#extracttype").val() + "/" + $("#extractvalidation").val() + "/}}";
    $("#input_extract p").text(result);
    return false;
});


$('#form_bot_kb').submit(function() {
    var result = "{{db/" + $("#schema").val() + "/" + $("#column").val() + "/" + $("#condition").val() + "/}}";
    $("#input_extract p").text(result);
    return false;
});


$('#schema').on('change', function(){
    console.log("change");
    $('#submit').click();
});

<?php
    $Lbkb = WfEdit::getBotKBStructure($user_id, $bot_id);
    echo "var schema = {";
    foreach($Lbkb as $key=>$kb){
        echo "'".$key."': '".SmsWfUtils::put_together($kb, ":", "", ""). "', ";
    }
    echo "'x' : 'y' };";
?>

$("#schema").change(function(e){
    console.log(e);
    console.log($("#schema").val());
    var col = schema[$("#schema").val()];
    var col_array = col.split(":");
    console.log(col_array);
    for (var key in col_array) {
        console.log(col_array[key]);
        var o = new Option(col_array[key], col_array[key]);
      /// jquerify the DOM object 'o' so we can use the html method
        $(o).html(col_array[key]);
        $("#column").append(o);
    }
});

function onSubmit(){
    if ($("#column").val() == "--") return false;
    var pattern = "{{db/" + $("#schema").val() + "/" + $("#column").val() + "/" + $("#condition").val() + "/" + "}}";
    console.log(pattern);
    var sms = $("#input_desc<?php echo $count; ?>").val();
    console.log(sms);
    $("#input_desc<?php echo $count; ?>").val(sms + pattern);
    return false;
}


function setExtract(){
    var extract = { "string" : "/string/string/}}", "name" : "/string/name/}}", "email" : "/string/email/}}", "text" : "/string/text/}}",
            "phone" : "/string/phone/}}", "datetime" : "/datetime/datetime/}}", "date" : "/date/date/}}", "number" : "/number/number/}}",
            "integer" : "/integer/integer/}}", "decimal" : "/decimal/decimal/}}" };

    var etype = $("#extracttype").val();
    var ename = $("#extractname").val();
    var prefix = extract[etype];
    console.log(prefix);
    var pattern = "{{ex/" + ename + prefix;
    console.log(pattern);
    var extract = $("#input_extract<?php echo $count; ?>").val();
    console.log(extract);
    $("#input_extract<?php echo $count; ?>").val(extract +  pattern);
    return false;
}
</script>