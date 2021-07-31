<?php include('_header.php');

require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/classes/Aws.php');
require_once (__ROOT__ . '/classes/Record.php');

$aws = new Aws();
$uuid=$_GET['uuid'];
$video_mode=$_GET['video_mode'];
$device_name=$_GET['device_name'];

$record = new Record($uuid, $video_mode);
?>
   
    <div class="row">
        <center>
            <h2>Manage your recordings for <?php echo $device_name; ?></h2>
        </center>
        <hr />
    </div>
 
    <div class="well">
        <div class="row">
            <b> Your recordings:</b>
            <hr/>
        </div>
        <?php 
        $recordings = $record->listRecordings();
        foreach ($recordings as $record){
        ?>
        <div class="row"> 
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <b><?php echo $record; ?></b>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <a href="/index.php?view=<?php echo $video_mode == 1 ? RECORD_MP4PLAY: RECORD_PLAY; ?>&key=<?php echo $record; ?>&uuid=<?php echo $uuid; ?>" target="_blank">
                <span class="glyphicon  glyphicon-play"></span></a>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <a href="/views/record_delete.php?key=<?php echo $record; ?>&uuid=<?php echo $uuid; ?>" target="donothing">
                <span class="glyphicon  glyphicon-trash"></span></a>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <a href="/views/record_edit.php?key=<?php echo $record; ?>&uuid=<?php echo $uuid; ?>">
                <span class="glyphicon  glyphicon-pencil"></span></a>
            </div>
        </div>
        <?php 
        } ?>
   </div>
   <div class="row">
      <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-1 col-md-offset-2 col-lg-offset-3"> 
            <a href="javascript:location.reload(true)"><span class="glyphicon  glyphicon-refresh">&nbsp;Refresh Listing</span></a>
      </div>
   </div>
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
    
    
<?php include('_footer.php'); ?>