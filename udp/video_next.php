<?php
header('Content-type: text/html');
header('Access-Control-Allow-Origin: *');

$uuid = $_GET ['uuid'];
$ts = $_GET ['ts'];

$filelist = glob("/srv/www/udp1.ibeyonde.com/public_html/live_cache/".$uuid."/*.mp4");
usort($filelist, function($a, $b) {
    return filemtime($a) > filemtime($b);
});

if (count($filelist) ==0 ){
    echo "video/loading.mp4";
    die;
}

//error_log("File=======");
$target_file="video/loading.mp4";
foreach (array_slice($filelist, 0, 4) as $file){
    //error_log("File=".$file.", ts=".filemtime($file));
    if (filemtime($file)>$ts){
        $target_file = $file;
        break;
    }
}
#echo print_r($filelist, true);
echo "/live_cache/".$uuid."/".basename($target_file)."-".filemtime($target_file);

?>
