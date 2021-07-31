<!DOCTYPE html>
<html lang="en">
<head>
<title>IbeyondE</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script
    src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script
    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link
    href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"  rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/headercss.css">
</head>
<?php 
define ( '__ROOT__', dirname ( __FILE__ ));
require_once(__ROOT__ .'/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');


$id=$_GET['id'];
$share_val=base64_decode($id);
$share_v=explode("@",$share_val);
$share_name=$share_v[0];
$uuid=$share_v[1];

$utils = new Utils();
if ($utils->validateShare($share_name, $uuid) == null){
    echo "Unauthorized !";
    die;
}
$animate=$_GET['animate'];
$datetime = Utils::datetimeNow ();
$client = new Aws();

if ($animate == 'true') {
    $ivs = $client->latestMotionDataUrls($uuid);
    $utils->publishNetwork($uuid, date( 'Y/m/d H:i:s e', $datetime), 15360);
?>
   <div class="container">
    <div class="col-sm-12 col-md-12">
        <div id="carousel-<?php echo $uuid; ?>" class="carousel slide carousel-fade" data-ride="carousel"  data-interval="1500" data-pause="hover" style="width: 432px; height:324px; margin: 0 auto">
             
             <div class="carousel-inner">
                   <?php 
                   $i=0;
                   foreach (array_reverse($ivs) as $iv) {
                       list($furl, $time) = $iv;
                       if ($i==0){
                   ?>
                    <div class="item active">
                    <?php 
                       }
                       else {
                    ?>
                    <div class="item">
                    <?php 
                       }
                       ?>
                        <img src="<?php echo $furl; ?>" alt="<?php echo $time; ?>" class="img-responsive" />
                        <?php if ($i==0){ ?>
                        <div class="carousel-caption">
                                <h1></h1>
                        </div>
                        <?php } ?>
                    </div>
                   <?php 
                      $i++;
                   }
                   ?>
 
             </div>
       </div>
       </div>
   </div>
<?php 
}
else {
    list($furl, $today)= $client->latestMotionDataUrl($uuid);
?>
    <img  class="img-responsive" src="<?php echo $furl ?>"  alt="Loading.."  width="432" height="324"  >
    
<?php
}
?>
