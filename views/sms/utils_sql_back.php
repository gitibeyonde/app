
<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
include_once (__ROOT__ . '/classes/core/Log.php');
include_once (__ROOT__ . '/classes/core/SqliteCrud.php');
include_once (__ROOT__ . '/classes/core/Icons.php');
require_once (__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/wf/utils/WfUtils.php');
require_once (__ROOT__ . '/a/utls.php');

include(__ROOT__.'/views/_header.php');

$log = $_SESSION ['log'] = new Log ("info");

$submit = (isset ( $_POST ['submit'] ) ? $_POST ['submit'] : null);
$tabella = (isset ( $_POST ['tabella'] ) ? $_POST ['tabella'] : null);

$user_id = $_SESSION['user_id'];

$content = "";
$error = "";
$Sql = new SqliteCrud ($user_id);
if ($submit == "db_view") {
} else if ($submit == "sql") {
    $sql = $_POST ['sql'];
    try {
        $content = $Sql->query ( $sql );
        $log->debug ( "Result=" . SmsWfUtils::flatten($content) );
    }catch(Exception $e){
        error_log(print_r($e, true));
        $error = "Bad sql :&emsp;".$sql;
    }
    if ($content === null){
        $error = $error."<br/>Returned nothing";
    }
    else if ($content === true) {
        $error = $error."<br/>Executed Successfully";
    }
    else if ($content === false){
        $error = $error."<br/>Execution Failed";
    }
    else {
        $submit = "content";
        error_log("Content=".print_r($content, true));
    }
} else if ($submit == "structure") {
    $structure = $Sql->schema ( $tabella );
} else if ($submit == "content") {
    $content = $Sql->data ( $tabella );
    $submit = "content";
}  else if ($submit == "add_row") {
    try {
        $Sql->insert($tabella, array($_POST));
    }catch(Exception $e){
        error_log(print_r($e, true));
        $error = "Bad Action :&emsp; Add row";
    }
    $content = $Sql->data ( $tabella );
    $submit = "content";
}  else if ($submit == "delete_row") {
    $rowid=$_POST['rowid'];
    $Sql->delete($tabella, $rowid);
    $content = $Sql->data ( $tabella );
    $submit = "content";
} 
else if ($submit == "uploadcsv"){
    $name=$_POST['name'];
    $filename = $_FILES["fileToUpload"]["tmp_name"];
    try {
        $Sql->loadWFData($name, $filename);
    }
    catch (Exception $e){
        $_SESSION['message']  = "Upload Failed". $e->getMessage();
    }
}
$Ltabella = $Sql->ls ();

$Icon = new Icons ();


function genForm($label, $bmsg, $submit, $icon, $isize, $icolor, $nv, $hnv, $width){
    $form = "";
    if ($icon=="trash_can") {
        $form = $form . '<form action="/index.php?view=utils_sql" method="post" width="100%" onsubmit="return confirm(\'Do you really want delete this ?\');">';
    }
    else {
        $form = $form . '<form action="/index.php?view=utils_sql" method="post" width="100%">';
    }
    $form = $form . '<form action="/index.php?view=utils_sql" method="post" width="100%">';
    foreach($hnv as $name=>$value){
        $form = $form .'<input type=hidden name="'.$name.'" value="'.$value.'" style="width: '.$width.'%;">';
    }
    $form = $form .'<label>'.$label.'</label>';
    foreach($nv as $name=>$value){
        $form = $form .'<input type=text name="'.$name.'" value="'.$value.'" style="width: '.$width.'%;">';
    }
    $form = $form .'<button type="submit" name="submit" value="'.$submit.'" style="background: transparent; border: 0px;" data-toggle="tooltip" data-placement="right" title="'.$bmsg.'">';
    $Icon = new Icons();
    $form = $form .$Icon->get($icon, $isize, $icolor);
    $form = $form .' </button></form>';
    echo $form;
}

?>
<body>
<div class="container" style="padding-top: 120px;">
    <div class="chat-display-card">
        <div class="row">
             <form class="form-inline"  action="/index.php"  method="get">
             <input type=hidden name=view value="<?php echo WORKFLOW_LISTING; ?>">
        	 <button style="background: transparent; border: 0px;"><?php echo $Icon->get("arrow_left", "1.5", "blue"); ?></button>
        	</form>
            <h4>Form Manager</h4>
            <p>&emsp;&beta;&emsp;Embed rich forms in your catalog</p>
        </div>
        <hr />
        <?php if ($submit == "content") {  ?>
         <h3><?php echo $tabella; ?></h3>
        <table width="100%" style="background: ghostwhite;">
            <tr style="background: lightgrey;"><!-- COLUMN NAMES -->
         <?php 
         if (sizeof($content) != 0)
          foreach ($content[0] as $key=>$row) {
            if ($key=="rowid")continue;
             ?>
            <td><?php echo $key; ?></td>
         <?php } ?>
           <td>Delete</td>
         </tr><!-- ROW LISTING -->
            <?php foreach ($content as $contentrow) {
                echo "<tr>";
                foreach ($contentrow as $key=>$value) {
                    if ($key=="rowid"){
                        $rowid=$value;
                        continue;
                    }
                    ?>
                    <td><?php echo $value; ?></td>
                 <?php } ?>
                  <td>
                  <?php genForm("", "Delete row", "delete_row", "trash_can", "1", "red", array(), array("tabella" => $tabella, "rowid" => $rowid), 10); ?>
            </td>
           </tr>
          <?php  } ?>
          <tr> <!-- INSERT COLUMNS -->
          <form action="/index.php?view=utils_sql" method="post" width="100%">
            <input type=hidden name="tabella" value="<?php echo $tabella; ?>">
             <?php 
             if (sizeof($content) != 0)
              foreach ($content[0] as $key=>$row) {
                if ($key=="rowid")continue;?>
                <td>
                    <input type=text name="<?php echo $key; ?>" value="" required>
                </td>
             <?php } ?>
             <td>
                <button type="submit" name="submit" value="add_row" style="background: transparent; border: 0px;" 
                data-toggle="tooltip" data-placement="right" title="Add Row">
                <?php echo $Icon->get("plus_square_fill", "1.5", "blue"); ?></button>
             </td>
              </form>
           </tr>
           </table>
        <div class="chat-input-card">
            <div class="bottom">
            <h8><?php echo $error; ?></h8>
            <?php genForm("", "Back to table listing", "db_view", "arrow_left", "3", "blue", array(), array(), 10); ?>
            </div>
        </div>
        <?php } else if ($submit == "structure") {  ?>
            <h3><?php echo $tabella; ?> structure</h3>
        <table width="100%" style="background: ghostwhite;">
            <tr style="background: lightgrey;">
                <td>Column</td>
                <td>Type</td>
                <td>Default</td>
                <td>Pk</td>
            </tr>
            <?php foreach ($structure as $row) {?>
                  <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['dflt_value']; ?></td>
                <td><?php echo $row['pk']; ?></td>
            </tr>
           <?php } ?>
           </table>
        <div class="chat-input-card">
            <div class="bottom">
            <h8><?php echo $error; ?></h8>
             <?php genForm("", "Back to table listing", "db_view", "arrow_left", "3", "blue", array(), array(), 10); ?>
            </div>
        </div>
           
        <?php
        } else {
            ?>
            <div class="row">
            <table width="100%" style="background: ghostwhite;">
                <tr style="background: lightgrey;">
                    <td>Name</td>
                    <td>Rows</td>
                    <td>Struct</td>
                    <td>Content</td>
                </tr>
            <?php foreach ( $Ltabella as $tabella ) { ?>
                <tr>
                    <td><?php echo $tabella;?></td>
                    <td><?php echo $Sql->row_count($tabella); ?></td>
                    <td>
                    <?php genForm("", "Show table structure", "structure", "columns", "2", "green", array(), array("tabella"=>$tabella),  10); ?>
                    </td>
                    <td>
                    <?php genForm("", "Load table content", "content", "card_list", "2", "black", array(), array("tabella"=>$tabella),  10); ?>
                   </td>
                </tr>
           <?php  } ?> 
            </table>
        </div>
        <div class="chat-input-card">
            <div class="bottom">
            <h8><?php echo $error; ?></h8>
                <?php genForm("Sql>", "Enter the sql", "sql", "arrow_return_left", "3", "green", array("sql"=>""), array(),  80); ?>
            </div>
        </div>
       <?php   } ?>
        
    </div>
    <div class="row"> 
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
        <a href="/catalog-maker/docs/sql-manager-help" target="_blank">Help</a>
      </div> 
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
        
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
        <a class="user-guide-panel" data-toggle="collapse"  data-target="#collapseTwo">Upload CSV</a>
      </div>
    </div>
    <div class="row">
              <div id="collapseTwo" class="panel-collapse collapse" style="width: 100%;">
                <div class="panel-body" style="padding: 20px;">
                  <form class="form-inline" action="/index.php?view=utils_sql"  method="post" enctype="multipart/form-data">
                        <label for="name">Schema Name</label>
                        <input class="form-control" type="text" id="name" placeholder="Enter Schema Name" name="name" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    
                        <label for="fileToUpload">CSV File</label>
                        <input class="form-control border" type="file" name="fileToUpload" id="fileToUpload">
                        <button type="submit" name="submit" value="uploadcsv" class="form-control btn btn-sim1 btn-block">Add</button>
                  </form> 
                </div>
              </div>
    </div>
</div>

<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>