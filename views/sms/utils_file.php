
<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
include_once (__ROOT__ . '/classes/core/Log.php');
include_once (__ROOT__ . '/classes/sms/AwsStore.php');
require_once (__ROOT__ . '/classes/wf/utils/WfUtils.php');
include_once (__ROOT__ . '/classes/core/Sqlite.php');
include_once (__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/core/Icons.php');

include(__ROOT__.'/views/_header.php');

$log = $_SESSION ['log'] = new Log ("info");

$submit = (isset ( $_POST ['submit'] ) ? $_POST ['submit'] : null);

$user_id = $_SESSION['user_id'];

$Aws = new AwsStore ();
$expandedfolder=null;
if ($submit == "upload") {
    $expandfolder = $folder = $_POST ['folder'];
    $filename = $_FILES ["fileToUpload"] ["tmp_name"];
    $log->debug ( "Upload file=" . $_FILES ["fileToUpload"] ["name"] );
    $r = $Aws->uploadUserFileToSimOnline ( $user_id, $_FILES, $folder );
    $log->debug ( "Result=" . $r );
} else if ($submit == "delete") {
    $expandfolder = $_POST ['folder'];
    $filename = $_POST ['file'];
    $r = $Aws->deleteFile ( $filename );
    $log->debug ( "Deleting f=" . $r );
} else if ($submit == "addfolder") {
    $folder = $_POST ['folder'];
    $r = $Aws->addFolder ( $user_id, $folder );
    $log->debug ( $r . "Adding folder f=" . $folder );
} else if ($submit == "expandfolder") {
    $expandfolder = $_POST ['folder'];
} else if ($submit == "deletefolder") {
    $folder = $_POST ['folder'];
    $r = $Aws->deleteFolder( $user_id, $folder);
    $log->debug ( "Deleting folder=" . $r );
} 

$Icon = new Icons();


function genForm($label, $bmsg, $submit, $icon, $isize, $icolor, $nv, $hnv, $width){
    $form = "";
    if ($icon=="trash_can") {
        $form = $form . '<form action="/index.php?view=utils_file" method="post" width="100%" onsubmit="return confirm(\'Do you really want delete this ?\');">';
    }
    else {
        $form = $form . '<form action="/index.php?view=utils_file" method="post" width="100%">';
    }
    $form = $form . '<form action="/index.php?view=utils_file" method="post" width="100%">';
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
        <div class="row">
             <form class="form-inline"  action="/index.php"  method="get">
             <input type=hidden name=view value="<?php echo WORKFLOW_LISTING; ?>">
        	 <button style="background: transparent; border: 0px;"><?php echo $Icon->get("arrow_left", "1.5", "blue"); ?></button>
        	</form>
            <h4>Upload Manager&emsp;&beta;&emsp;Allow for photo/image upload in the catalogs</h4>
        </div>
        <hr/>
        <div class="row">
            <table>
                <tr>
                    <td>
                        <p>Add Folder</p>
                    </td>
                    <td>
                        <form action="/index.php?view=utils_file" method="post">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <input type=text name="folder" value="" required>
                                        <button type="submit" name="submit" value="addfolder" style="background: transparent;border: 0px;" 
                                               data-toggle="tooltip" data-placement="right" title="Add folder to /">
                                          <?php echo $Icon->get("folder", "1.5", "green"); ?>
                                      </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <hr/>
        <?php
        $folders = explode ( ",", $Aws->getFolders ( $user_id ) );
        foreach ( $folders as $folder ) {
            if (isset($expandfolder) && $folder == $expandfolder) {
                $awsfiles = $Aws->getFileListingForFolder($user_id, $folder)
            ?>
                 <table>
                    <tr>
                        <td style="padding-bottom: 20px;"><?php echo "/".$folder; ?></a></td>
                        <!-- Upload File -->
                        <td>
                            <form action="/index.php?view=utils_file" method="post" enctype="multipart/form-data">
                                <input class="form-control"  type="file" name="fileToUpload" required> 
                         </td>
                         <td style="padding-bottom: 20px;">
                                <input type=hidden name="folder" value="<?php echo $folder; ?>">
                                <button type="submit" name="submit" value="upload"  style="background: transparent;border: 0px;" 
                                    data-toggle="tooltip" data-placement="right" title="Upload file to this folder">
                                    <?php echo $Icon->get("cloud_upload", "1.5", "blue"); ?>
                                </button>
                            </form>
                        </td>
                    <?php foreach ($awsfiles as $awsfile) {
                        $awsfileurl = $Aws->getSignedFileUrl($awsfile)
                        ?> 
                      <tr>
                        <td></td>
                        <td><a href="<?php echo $awsfileurl;?>" target="_blank"><?php echo basename($awsfile); ?></a>
                        </td>
                        <td  style="padding-top: 20px;">
                            <?php genForm( "", "Delete the file", "delete", "trash_can", "1", "red", array(), array("file" => $awsfile, "folder" => $folder), 10); ?>
           
                         </td>
                       </tr>
                    <?php } 
                    if (count($awsfiles) == 0) { //0 files folder can be deleted ?>
                          <td>
                            <?php genForm( "", "Delete this folder", "deletefolder", "trash_can", "1", "red", array(), array("folder" => $folder), 10); ?>
                           </td>
                    <?php } ?>
                    </tr>
                </table>
       <?php } else { ?>
                 <table>
                    <tr>
                        <td style="padding-top: 20px;">
                            <?php genForm( "", "Open this folder", "expandfolder", "folder_open", "1", "blue", array(), array("folder" => $folder), 10); ?>
                         </td>
                        <td><?php echo "/".$folder; ?></a></td>
                    </tr>
                </table>
       <?php  } }  ?>
    <hr/>
    <div class="row"> 
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
        <a href="/catalog-maker/docs/file-manager-help" target="_blank">Help</a>
      </div> 
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
        
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-4">
      </div>
    </div>
</div>

<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>