<?php

define ( '__ROOT__',  dirname (dirname ( __FILE__ )));

require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Motion.php');
require_once (__ROOT__ . '/classes/UserFactory.php');

include('_header.php');

$aws = new Aws ();
$uuid = $_GET ['uuid'];
$furl = $_GET['furl'];
$time = $_GET['time'];
$date = $_GET['date'];
$user_email = $_SESSION ['user_email'];
$user_name = $_SESSION ['user_name'];
$user = UserFactory::getUser ( $user_name, $user_email );
$devices = $user->getDevices ();
?>

<head>
<title>Ibeyonde</title>
<script src="/js/prototype.js" type="text/javascript"></script>
<script src="/js/scriptaculous.js?load=effects,builder,dragdrop" type="text/javascript"></script>
<script src="/js/cropper.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
Event.observe ( 
  window, 
  'load', 
  function() {

	var x = document.getElementById("fromt");
	var y = document.getElementById("tot")
	
	for (var i = 00;i<24;i++)
	{	
	  var option = document.createElement("option");
	  var option1 = document.createElement("option");
	  var j = ("0" + i).slice(-2);
	  option.value = j 
	  option.text = j+":00:00";
	  option1.value = j; 
	  option1.text = j+":00:00";;
	  x.add(option, x[0]);
	  y.add(option1, y[0]);
	}  
    new Cropper.Img ( 
      'himage',
      {
        minWidth: 100, 
        minHeight: 100,
        displayOnInit: true, 
        onEndCrop: saveCoords,
        onloadCoords: { x1: 0, y1: 0, x2: 100, y2: 100 },
      }
    )
    document.getElementById('from').valueAsDate=new Date();
    document.getElementById('to').valueAsDate=new Date();

  }
);
        
function saveCoords (coords, dimensions)
{
  $( 'x1' ).value = coords.x1;
  console.log(coords.x1)
  console.log(coords.y1)
  console.log(dimensions.width)
  console.log(dimensions.height)
  $( 'y1' ).value = coords.y1;
  $( 'width' ).value = dimensions.width;
  $( 'height' ).value = dimensions.height;
  $( 'hheight' ).value =document.getElementById('himage').clientHeight;
  $( 'hwidth' ).value = document.getElementById('himage').clientWidth;
} 

function getcount()
{
	var xmlhttp = new XMLHttpRequest();
	var image = <?php echo "'" . $furl . "'"; ?> ;
	var next_url = "https://bingo.ibeyonde.com:5081/?cmd=classify&image="+image;
	xmlhttp.onreadystatechange = function() {

		if (this.readyState == 4 && this.status == 200) {

			var responseText = JSON.parse(this.responseText);
			$('count').append(responseText['label']);
			$('Bcount').hide();

	        
	    }

		
	    }

	xmlhttp.open("GET", next_url, true);
	xmlhttp.send();
    
	};
		


</script>
    
<style>
    .box-shadow {
        transition: 1s;
        box-shadow: 4px 4px 10px 1px #ccc;  
    }
    .card:hover{
        box-shadow: 8px 8px 10px 1px #ccc;
    }
    .card .card-image{
        overflow: hidden;
        -webkit-transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -ms-transform-style: preserve-3d;
        -o-transform-style: preserve-3d;
        transform-style: preserve-3d;
    }
    .flex-container2{
        display:flex;
        flex-flow: wrap;
        justify-content: space-around;
        margin-top: 4px;
    }
    
    .input-field{
        background-color: transparent;
        border: 0px;
        border-bottom: 2px solid gray;
        margin-bottom: 2px;
        font-size: 14px;
        color: black;
         width: 32%;
    }
    
    .input-field:hover{
        background-color: transparent;
        color: black;
        border-bottom: 2px solid coral;
       
    }
    
    
    label{
        font-family: 'Didact Gothic', sans-serif;
        font-size: 14px;
        font-weight: bold;
    }
    
    .link-button1{
        color: #365e88;
        background: transparent;
        border-radius: .2rem !important;
        border: 1px solid #365e88 !important;
    }
    
    .link-button1:hover{
        background: linear-gradient(to right, #38628c, #133558) !important;
        color: white;
        cursor: pointer;
    }
    
    .border-default {
    border: 1px solid #1fc3ab26;
    padding: 6px;
    border-radius: 9px;
}
    
    </style>
</head>
<body>
<main class="mt-5" role="main">
    <div class="album py-5 bg-none">
       <div class="container-fluid top"> 
           <div class="row">
               <div class="col-md-4 col-sm-12 col-12 col-lg-4">
               <div class="card mb-4 box-shadow">
               <div class="card-image">
                   <img class="img-fluid" id="himage" src="<?php echo $aws->getSignedFileUrl ($furl); ?>" alt="Loading..." width="100%" height="100%"/>
                  </div>
                   
                   <div class="card-body">
                <form action="search/saveCrop.php" method="post" target="_blank">   
                <input type="hidden" name="x1" id="x1" value=""/>
                <input type="hidden" name="y1" id="y1" value=""/>

                <input type=hidden name=uuid value="<?php echo $uuid ?>" />
                <input type="hidden" name="history_width" id="hwidth" value=""/>
                <input type="hidden" name="width" id="width" value=""/>
                <input type=hidden name=url value="<?php echo $furl ?>" />
                <input type="hidden" name="height" id="height" value=""/>
                <input type="hidden" name="history_height" id="hheight" value=""/>
                  <div class="border-default"> 
                 <div class="flex-container2"> 
                <label>From: </label><input class="input-field" type="date" id="from" name="from_date"/>
                   
                <label>To: </label><input class="input-field" type="date" id="to" name="to_date"/>
                </div>
                
                 <div class="flex-container2">    
                <label>From: </label>
                <select class="input-field" name="ft" id="fromt"> </select>
                <label>To: </label> 
                <select class="input-field" name="tt" id="tot"></select> 
                    </div>
                    <div style="margin-left: 4px; margin-top: 4px;"> 
                      <label >Cameras:</label>
                  <?php
                 
                 foreach ($devices as $device)
                 {
                    
                     $setting=(array)json_decode($device->setting);
                     
                     if(!isset($setting['video_mode']) || $setting['video_mode']==0)
                     {
                        echo '<input style="margin-top:-10px;" type="checkbox" name="camera" value='.$device->uuid.'> <label style="margin-right: 2px">'.$device->device_name.'</label>';
                     }
                 }
                 
                 ?>
                    </div>
                       
                    <div class="flex-container2">
                    <input style="font-size:16px" class="link-button1" type="submit" name="Done" value=" Search "/>
                    </div>
                    </div>
                       </form>
                       <br>
              
                  <form class="form-horizontal" name=tagPicture method=GET action="../sql_action.php">
                      <input type=hidden name=view value="<?php echo FOR_IMAGE_HEADER ?>" /> 
                      <input type=hidden name=action value="TagPicture" /> 
                      <input type=hidden name=uuid value="<?php echo $uuid ?>" /> 
                      <input type=hidden name=time value="<?php echo $time ?>" /> 
                      <input type=hidden name=date value="<?php echo $date ?>" /> 
                      <input type=hidden name=url value="<?php echo $furl ?>" />
                      
                      <div class="border-default">
                      <label> Name: </label>
                      <input class="input-field" type="text" name="tag_name" value=""/> 
                      <button style="font-size: 14px;" class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="submit">Tag</button>
                      <button style="font-size: 14px;" class="btn btn-sm btn-outline-secondary" type="submit" name="submit" value="cancel">Cancel</button>
                      </div>
                      
                </form>
                   <div class="border-default"> 
                  <div id="count"><label>Classification:</label></div><input style="font-size: 14px" class="link-button1" type="button" id="Bcount" value="Classify" onclick="getcount()"/>
                   </div>
                       </div>
                   </div>
</div>
               </div></div></div>
</main>
</body>
<?php 
include ('_footer.php'); ?>