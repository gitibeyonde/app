<?php
define ('__ROOT__', dirname(dirname ( dirname ( __FILE__ ))));
require_once (__ROOT__ . '/classes/Aws.php');
$aws = new Aws ();

$x1 = $_POST['x1'];
$y1 = $_POST['y1'];
$width = $_POST['width'];
$height = $_POST['height'];
$hwidth = $_POST['history_width'];
$hheight = $_POST['history_height'];
$furl = $_POST['url'];
$url = $aws->getSignedFileUrl($furl);
$srcImg  = imagecreatefromjpeg($url);
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$cameras = $_POST['camera'];
$from_time = $_POST['ft'];
$to_time = $_POST['tt'];
?>


<html>
<head>
	<title>Ibeyonde</title>
	<script>

function getImages(){
openModal();	
var xmlhttp = new XMLHttpRequest();

var fromdate = <?php echo "'".$from_date."'"; ?>;
var to_date = <?php echo "'".$to_date."'"; ?>;
var url = <?php echo "'".$furl."'"; ?>;
var x1 = <?php echo "'".$x1."'"; ?>;
var y1 = <?php echo "'".$y1."'"; ?>;
var from_time = <?php echo "'".$from_time."'"; ?>;
var to_time = <?php echo "'".$to_time."'"; ?>;
var height = <?php echo "'".$height."'"; ?>;
var width = <?php echo "'".$width."'"; ?>;
var hheight = <?php echo "'".$hheight."'"; ?>;
var cameras = <?php echo json_encode($cameras); ?>;
var hwidth = <?php echo "'".$hwidth."'"; ?>;
var ajax_url = "https://bingo.ibeyonde.com:5081/?cmd=search&fromdate="+fromdate+"&to_date="+to_date+"&image="+url+"&x1="+x1+"&y1="+y1+"&hheight="+hheight+"&hwidth="+hwidth+"&height="+height+"&width="+width+"&cameras="+cameras+"&from_time="+from_time+"&to_time="+to_time;
console.log(ajax_url);                      


xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        closeModal();
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", ajax_url, true);
xmlhttp.send();

};

function myFunction(arr) {
    var out = "";
    var i;
    var val;
        //alert(arr.length);
    for(var key in arr) {
        
        
        out += '<img src="' + arr[key] + '" height="300" width="400"/>';
    }
    document.getElementById("Images").innerHTML = out;
}

function openModal() {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
}


</script>
<style>
#fade {
    display: none;
    position:absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #ababab;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .70;
    filter: alpha(opacity=80);
}

#modal {
    display: none;
    
}


</style>
</head>
<body onload="getImages()">
    <h1>Search Results</h1>
	<div id="Images">
	</div>
    <div id="fade"></div>
    <div id="modal">
        <h5>Please wait while the search is loading</h5>
        <img id="loader" src="Loading_icon.gif" />
    </div>
</body>
</html>