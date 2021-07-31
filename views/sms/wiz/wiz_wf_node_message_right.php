

<div class="col-lg-3 col-md-3 sel1" >
<div class="container">

    <div id="message_kb_tool">

        <?php
        $Lbkb = WfEdit::getBotKBStructure($user_id, $bot_id);
        if (count($Lbkb) == 0 ){
            echo '<div class="row sel2">';
            echo "<h8 class='btn-sim2'>No KB Tables</h8>";
            echo '</div>';
        }
        else {
        ?>
     <h3>Substitute from KB</h3>

       <div class="row">
         <form id="from_bot_kb" action="#" method="get" onSubmit="return onKBSubmit();">
              <input type="hidden" id="viewname" name="view" value="workflow_node">
              <?php if (count($Lbkb) == 1 ){ ?>
                <label for="schema"><b>from</b> <?php echo key($Lbkb); ?></label>
                <input type="hidden" id="schema" name="schema" value="<?php echo key($Lbkb); ?>">
                <br/>
                <label><b>select </b></label>
                <?php foreach (current($Lbkb) as $col) { ?>
                    <input type="checkbox" name="column" value="<?php echo $col; ?>">
                    <label><?php echo $col; ?> </label>
                <?php } ?>
                <br/>
                <label for="condition"><b>where</b> </label>
                 <select id="comp_col" name="comp_col">
                   <option value="--">--</option>
                    <?php foreach (current($Lbkb) as $col) { ?>
                        <option value="<?php echo $col; ?>"><?php echo $col; ?></option>
                    <?php } ?>
                 </select>

                 <select id="comp_op" name="comp_op">
                        <option value="==">equal to</option>
                        <option value="!=">not equal to</option>
                 </select>

                <input type=text id="comp_val" name="comp_val" list="extracted" style="height: 25px;width: 120px;"></input>
                  <datalist id="extracted">
                     <?php
                       $Lbkb = WfEdit::getWorkflowExtractionsString($WFDB->getNodes($bot_id));
                        foreach($Lbkb as $tn=>$col){
                         ?>
                        <option>user value <?php echo $tn; ?></option>
                     <?php } ?>
                 </datalist>

              <?php } else { ?>

              <label for="schema"><b>from</b></label>
               <select id="schema" name="schema">
                   <option value="--">--</option>
                     <?php foreach($Lbkb as $tn=>$col){ ?>
                        <option value="<?php echo $tn; ?>"><?php echo $tn; ?></option>
                     <?php } ?>
                </select>
                <br/>
                <label><b>select </b></label>
                <div id="checkbox_column_container"></div>
                <br/>
               <label for="condition"><b>where</b> </label>
                <select id="comp_col" name="comp_col">
                   <option value="--">--</option>
                </select>

                <select id="comp_op" name="comp_op">
                        <option value="==">equal to</option>
                        <option value="!=">not equal to</option>
                </select>

               <input type=text id="comp_val" name="comp_val" list="extracted" style="height: 25px;width: 120px;"></input>
                <datalist id="extracted">
                     <?php
                       $Lbkb = WfEdit::getWorkflowExtractionsString($WFDB->getNodes($bot_id));
                        foreach($Lbkb as $tn=>$col){
                         ?>
                        <option>user value <?php echo $tn; ?></option>
                     <?php } ?>
               </datalist>
             <?php } ?>
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                <input type=hidden name=user_id value="<?php echo $user_id; ?>">
              <button id="submit" type="submit" name="submit" value="addsubstitutekbs" class="btn btn-sim3"
            data-toggle="tooltip" data-placement="top" title="Place your cursor in the html editor before clicking on add button.">add</button>
        </form>
      </div>
    <?php } ?>
    </div>


    <div id="message_user_data_tool">

      <?php
        $Lbkb = WfEdit::getWorkflowExtractionsString($WFDB->getNodes($bot_id));
        if (count($Lbkb) == 0 ){
            echo '<div class="row sel2">';
            echo "<h8 class='btn-sim2'>No User Data</h8>";
            echo '</div>';
        }
        else {
      ?>

     <br/>
      <div class="row sel2">
        <h3>Substitute from user Data</h3>
      </div>

      <div class="row">
         <form id="form1" action="#" method="get"  onSubmit="return setUserSubs();">
             <input type="hidden" id="viewname" name="view" value="workflow_node">

              <label for="condition">Name:</label>
               <input type=text id="extract_name" name="extract_name" list="extracted" style="height: 25px;width: 120px;"></input>
                <datalist id="extracted">
                     <?php
                       $Lbkb = WfEdit::getWorkflowExtractionsString($WFDB->getNodes($bot_id));
                        foreach($Lbkb as $tn=>$col){
                         ?>
                        <option><?php echo $tn; ?></option>
                     <?php } ?>
               </datalist>
               <br/>
               <input type="radio" name="ud_action" value="-"  onClick="return onClickInitUDReset()">
               <label>reset</label>
               <input type="radio" name="ud_action" value="+" onClick="return onClickInitUDAdd()">
               <label>init</label>
               <input type="text" name="ud_init_val" value="" Placeholder="Init Val" style="display: none">

                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                <input type=hidden name=user_id value="<?php echo $user_id; ?>">
               <br/>
            <button id="submit" type="submit" name="submit" value="addsubstituteuser" class="btn btn-sim3"
            data-toggle="tooltip" data-placement="top" title="Place your cursor in the html editor before clicking on add button.">add</button>
        </form>
      </div>
      <?php } ?>
    </div>



    <div id="message_image_tool">
       <br/>
        <div class="row sel2">
            <h3>Insert Image</h3>
        </div>
        <div class="row">
            <?php $Limges = WfEdit::getImageList($bot_id);
            foreach($Limges as $imgurl){
                $name = basename($imgurl);
                $jsname = preg_replace("/[^A-Za-z0-9]/", '', $name);;
                echo "<img id='".$jsname."' src='".$imgurl."' width='25%' height='25%'  style='padding: 5px;'>";
            }
            ?>
        </div>
    </div>



    <div id="forms_tool">
       <br/>
        <div class="row sel2">
            <h3>Insert Form</h3>
        </div>
        <br/>
        <div class="row">
            <?php $forms = WfEdit::getForms($user_id);
            foreach($forms as $f){
                if ($f == "form_metadata")continue;
                echo "<h1 id=".$f." class='img-box'>".$f."</h1>";
            }
            ?>
        </div>
    </div>

</div>
</div>
 <script>
 <?php $Limges = WfEdit::getImageList($bot_id);
 foreach($Limges as $imgurl){
     $name = basename($imgurl);
     $jsname = preg_replace("/[^A-Za-z0-9]/", '', $name);;
     ?>
 $("#<?php echo $jsname; ?>").click(function(){
     var src = '<img class="img-fluid" src="<?php echo $imgurl; ?>">\n' ;
     console.log("Img URL=" + src);
     insertAtCursor(src);
  });
 <?php } ?>

 <?php $forms = WfEdit::getForms($user_id);
 foreach($forms as $f){ ?>
 $("#<?php echo $f; ?>").click(function(){
     var form_name = '<?php echo $f; ?>' ;
     console.log("Form name=" + form_name);
     insertAtCursor("<div class=form>" + form_name + "</div>");
  });
 <?php } ?>


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
         $('#checkbox_column_container')
                .append(
                 $('<label>').prop({
                     for: col_array[key]
                   }).html('&nbsp;' + col_array[key])
                 )
                 .append(
                 $('<input>').prop({
                   type: 'checkbox',
                   name: 'column',
                   value: col_array[key]
                 })
               );
         // Add to condition colums
         var o = new Option(col_array[key], col_array[key]);
         $(o).html(col_array[key]);
         $("#comp_col").append(o);

     }
 });

 function onKBSubmit(){
     var column_pattern = "";
     $("input:checkbox[name=column]:checked").each(function(){
         let col = $(this).val();
         if (col.includes('img')){
             column_pattern += "#IS || " + col + " || #SI || ";
         }
         else {
             column_pattern += "#SP || " + col + " ||";
         }
     });
     if(column_pattern.length == 0){
         alert("Please select some columns !");
         return false;
     }

     var pattern;
     console.log(column_pattern);
     console.log($("#comp_col").val() );
     console.log($("#comp_op").val() );
     console.log($("#comp_val").val() );
     if ($("#comp_col").val() == "--"){
        pattern = "<ul> {{db/" + $("#schema").val() + "/#LI || " + column_pattern + " #IL || #NL//" + "}} </ul>";
     }
     else {
         if ($("#comp_val").val().startsWith("user value ")){
             pattern = "{{db/" + $("#schema").val() + "/" + column_pattern + " #SP/" + $("#comp_col").val() +  $("#comp_op").val() +  ":" + $("#comp_val").val().substring(11) + "/" + "}}";
         }
         else if ($("#comp_val").val() != "" ) {
            pattern = "{{db/" + $("#schema").val() + "/" + column_pattern+ " #SP/" + $("#comp_col").val() +  $("#comp_op").val() +  "'" + $("#comp_val").val() + "'/" + "}}";
         }
         else {
             alert("The condition's value is not set !");
             return false;
         }
     }
     console.log(pattern);
     insertHtmlAtCursor(pattern);
     return false;
 }

 function setUserSubs(){
     var subs = $("#extract_name").val();
     console.log($("input[name=ud_init_val]").val());
     console.log($("input:radio[name=ud_action]:checked").val());
     var pattern;
     if ($("input:radio[name=ud_action]:checked").val() == "-"){
         pattern = "{{mydb/user_data/-/" + subs + "/" + "}}";
     }
     else if ($("input:radio[name=ud_action]:checked").val() == "+"){
         pattern = "{{mydb/user_data/+/" + subs + "/" + $("input[name=ud_init_val]").val() + "}}";
     }
     else {
        pattern = "{{mydb/user_data/value/" + subs + "/" + "}}";
     }
     console.log(pattern);
     insertTextAtCursor(pattern);
     return false;
 }

 function onClickInitUDAdd(){
     $("input[name=ud_init_val]").show()
 }
 function onClickInitUDReset(){
     $("input[name=ud_init_val]").hide()
 }

 $("input[type=text]").on('input', function() {
   var c = this.selectionStart,
       r = /[^a-z0-9-_ ]/gi,
       v = $(this).val();
   if(r.test(v)) {
     $(this).val(v.replace(r, ''));
     c--;
   }
   this.setSelectionRange(c, c);
 });
 </script>
