
<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserForm.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
$count = isset($_GET['count']) ? $_GET['count'] : 0;
$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$kb = new WfUserForm($user_id);

$col = array();
$input = array();
if ($submit == "addtable"){
    if ($count == 0) {
        $_SESSION['message']  = "Table creation requires at least one column";
    }
    else {
        for ($i=0;$i<$count; $i++){
            array_push($col, $_GET["col".$i]);
            array_push($input, $_GET["input".$i]);
        }
        $kb->createFormTable($tabella, $col, $input);
    }
    //reset
    $col = array();
    $input = array();
    $count=0;
    $tabella = null;
}
else  if ($submit == "addcolumn"){
    $count = $count +1;
    for ($i=0;$i<$count; $i++){
        array_push($col, $_GET["col".$i]);
        array_push($input, $_GET["input".$i]);
    }
}
else  if ($submit == "removecolumn"){
    $count = $count -1;
    for ($i=0;$i<$count; $i++){
        array_push($col, $_GET["col".$i]);
        array_push($input, $_GET["input".$i]);
    }
}
else if ($submit == "delete"){
    try {
        $kb->deleteForm($tabella);
    }
    catch (Exception $e){
        $_SESSION['message']  = "Delete Failed". $e->getMessage();
    }
}

?>
<body>
   <div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php');
     include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php');
     ?>


    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h4>Create Form</h4>
            <p>Create form by creating the input fields you want to be captured by the form.
            Provide name of the form field, and type of the form field.</p>

          	<form action="/index.php?"  method="get">
                <input type=hidden name=view value="<?php echo WIZ_FORM_CREATE; ?>">
                <input type=hidden name=user_id value="<?php echo $user_id; ?>">
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">

                <div class="form-group">
                    <label>Form Name</label>
                	<input type="text" name="tabella" value="<?php echo $tabella; ?>" Placeholder="Form Name" required>
               	</div>

                <?php for ($i=0;$i<$count; $i++) {
                    echo '<div class="form-group">';
                    echo '<input type=text name=col'.$i.' value="'.$col[$i].'" readonly>';
                    echo '<input type=text name=input'.$i.' value="'.$input[$i].'" readonly>';
                    if ($i == $count -1){
                        echo '<button class="btn btn-sim3" type="submit" name="submit" value="removecolumn">X</button>';
                    }
                    echo '</div>';

                 } ?>

                 <div class="form-group">
                    <label>Field Name</label>
                    <input type=text name="col<?php echo $count; ?>" value="" Placeholder="Field Name">

                    <label>Input Type</label>
                    <select id="input" name="input<?php echo $count; ?>"'.$i.'>
                    <option value="text">text</option>
                    <option value="number">number</option>
                    <option value="textarea">textarea</option>
                    <option value="tel">tel</option>
                    <option value="email">email</option>
                    <option value="file">file</option>
                    <option value="color">color</option>
                    <option value="date">date</option>
                    <option value="time">time</option>
                    <option value="url">url</option>
                    <option value="image">image</option>
                    <option value="week">week</option>
                    <option value="range">range</option>
                    </select>
                    <input type=hidden name=count value="<?php echo $count; ?>">
                	<button class="btn btn-sim3" type="submit" name="submit" value="addcolumn">+</button>
                </div>


                <div class="row">
                     <div class="form-group col-6">
                        	<button class="btn btn-sim1 btn-block" type="submit" name="submit" value="addtable">Create Form</button>
                	 </div>
                </div>
          </form>
      </div>
  </div>
<hr/>
  <div class="row">
    <div class="col">
        <h3>Forms</h3>
   </div>
  </div>

  <div class="row">
    <div class="col">
        Name
    </div>
    <div class="col">
        Row
    </div>
    <div class="col">
        Columns
    </div>
    <div class="col">
        Type Value
    </div>
    <div class="col">
        Change Type
    </div>
    <div class="col">
        Delete
    </div>
  </div>
  <hr/>

    <?php foreach( $kb->ls() as $tn){
    if ($tn == "form_metadata") continue;
        ?>
      <div class="row">
        <div class="col">
             <?php echo $tn; ?>
        </div>
        <div class="col">
             <?php echo $kb->row_count($tn); ?>
        </div>
        <div class="col">
             <?php echo SmsWfUtils::flatten($kb->t_columns($tn)); ?>
        </div>
        <div class="col">
            <?php echo $kb->getFormType($tn); ?>
        </div>
        <div class="col">
            <form action="/index.php"  method="get" style="float: left;" >
                <input type=hidden name=view value="<?php echo WIZ_FORM_UI; ?>">
                <input type=hidden name=tabella value="<?php echo $tn; ?>">
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                <button class="btn btn-sim1" type="submit" name="submit" value="arrange_form">Customize</button>
            </form>
        </div>
        <div class="col">
            <form action="/index.php"  method="get" style="float: left;" >
                <input type=hidden name=view value="<?php echo WIZ_FORM_CREATE ?>">
                <input type=hidden name=tabella value="<?php echo $tn; ?>">
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                <button class="btn btn-sim2" type="submit" name="submit" value="delete">Delete</button>
            </form>
        </div>
      </div>
  <?php } ?>



</div>


<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>