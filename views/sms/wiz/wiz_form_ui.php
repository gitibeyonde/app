<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserForm.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');

$_SESSION['log'] = new Log ("trace");

$_SESSION['message'] = "";
$user_id = $_SESSION['user_id'];

$kb = new WfUserForm($user_id);

$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;
$row_id = isset($_GET['row_id']) ? $_GET['row_id'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

if($submit == "save"){
    $type = $_GET['type'];
    error_log("Type=". $type);
    $kb->saveFormType($tabella, $type);
}
?>
<script>
function formUI(e){
    console.log("Button TYPE clicked " + e.value);
    $('#formType').text(e.value);
    if (e.value == "compact"){
        $('#embed_type').val("compact");
        $('#targetForm').addClass("form-inline");
        $('#targetForm div').each(
                function(index){
                    var input = $(this);
                    input.removeClass("row");
                }
            );
        $('#targetForm label').each(
        	    function(index){
        	        var input = $(this);
        	        input.hide();
                    input.removeClass("col-2");
                }
        	);
        $('#targetForm input, textarea').each(
                function(index){
                    var input = $(this);
                    input.removeClass("col-10");
                }
            );
    }
    else if (e.value == "large"){
        $('#embed_type').val("large");
        $('#targetForm').removeClass("form-inline");
        $('#targetForm div').each(
                function(index){
                    var input = $(this);
                    input.addClass("row");
                }
            );
        $('#targetForm label').each(
                function(index){
                    var input = $(this);
                    input.show();
                    input.addClass("col-2");
                }
            );
        $('#targetForm input, textarea').each(
                function(index){
                    var input = $(this);
                    input.addClass("col-10");
                }
            );
    }
    else if (e.value == "optimized"){
        $('#embed_type').val("optimized");
        $('#targetForm').addClass("form-inline");
        $('#targetForm div').each(
                function(index){
                    var input = $(this);
                    input.removeClass("row");
                }
            );
        $('#targetForm label').each(
                function(index){
                    var input = $(this);
                    input.show();
                    input.removeClass("col-2");
                }
            );
        $('#targetForm input, textarea').each(
                function(index){
                    var input = $(this);
                    input.removeClass("col-10");
                }
            );
    }
    else {
    	console.log("Unrecognized type "+ e.value);
    }
}


function checkRadios(e){
    console.log("Type " + $('#embed_type').val());
    if ($('#embed_type').val().trim() == "" ){
        alert("Please select a form type");
        return false;
    }
    return true;
}
</script>

<body>
   <div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php');
     include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php');
     ?>


<h2>Customize <?php echo $tabella; ?></h2>
<p>Choose form layout</p>
<form>
<label class="checkbox-inline"><input onchange="formUI(this);" type="radio" name="type" value="compact">Compact</label>
<label class="checkbox-inline"><input onchange="formUI(this);" type="radio" name="type" value="large">Large</label>
<label class="checkbox-inline"><input onchange="formUI(this);" type="radio" name="type" value="optimized">Optimized</label>
</form>
<div class="row" style="padding: 20px;background: lightgrey;">
  <div class="col-12">
    <form id="targetForm" onsubmit="return false;">
        <input type="hidden" name="created_at" value="#DATE_TIME">
        <input type="hidden" name="table" value="<?php echo $tabella; ?>">
    <?php
    foreach($kb->t_columns_types($tabella) as $col_type=>$type){
        list($col, $type) = explode("->", $col_type);
        ?>
        <div class="form-group">
        <label id="label" style="display: none;"><?php echo ucfirst($col); ?></label>
        <input class="form-control" type="<?php echo $type; ?>" name="<?php echo $col; ?>" placeholder="<?php echo $col; ?>">
        </div>

    <?php } ?>
        <div class="form-group">
          <button type="submit" name="submit" value="customise_add" class="btn">Submit</button>
        </div>
    </form>
  </div>
</div>


<hr/>
  <div class="row">
     <div class="col-6">
        <form  action="/index.php" method="get" width="100%" onsubmit="return checkRadios(this);">
            <input type=hidden name=view value="<?php echo WIZ_FORM_UI; ?>">
            <input type=hidden name=tabella value="<?php echo $tabella; ?>">
            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
            <input id="embed_type" type=hidden name=type value="">
          <button type="submit" name="submit" value="save" class="btn btn-sim4 btn-block">Save</button>
        </form>
    </div>
     <div class="col-6">
        <form  action="/index.php" method="get" width="100%">
            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
            <input type=hidden name=view value="<?php echo WIZ_FORM_CREATE; ?>">
          <button type="submit" name="submit" value="back" class="btn btn-sim2 btn-block">Back</button>
        </form>
    </div>
  </div>



</div>



<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>