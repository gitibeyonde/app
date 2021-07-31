<?php include('_header.php'); 

require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/classes/Aws.php');

$aws = new Aws();
$uuid=$_GET['uuid'];
$key=$_GET['key'];
$recordings = $aws->loadRecording($uuid, $key);
?>
   

<div class="container">
  

  <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
    
<script type="text/javascript" src="/js/sequencer.bg.js"></script> 

 <script type="text/javascript">
        Sequencer.init({list : [ 

<?php 
$count=0;
foreach ($recordings as $record){
    $furl = $aws->getSignedFileUrl($record->image);
            echo '"'.$furl.'", ';
            $count++;
    }
?>
] , folder:"", direction:"-x", playMode:"mouse"})
</script>

    </div>
  </div>
</div>

  <p>
  <b>   No of frames=<?php echo $count; ?>&nbsp;&nbsp;&nbsp;</b> 
  <a href="/views/record_delete.php?key=<?php echo $key; ?>&uuid=<?php echo $uuid; ?>">
                <span class="glyphicon  glyphicon-trash">&nbsp;<?php echo $key; ?></span></a>
  </p>
    
<?php include('_footer.php'); ?>