<style>

.box-shadow {
            
            transition: 1s;
    box-shadow: 8px 8px 10px 1px #ccc;
    
        }

        
.card:hover{
            
    box-shadow: none;
        }
    
.card .card-image{
    overflow: hidden;
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -ms-transform-style: preserve-3d;
    -o-transform-style: preserve-3d;
    transform-style: preserve-3d;
}
    
    .flex-container{
        display:flex;
        flex-flow: wrap;
        justify-content:flex-start;
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
    }
    
    
    



</style>
<?php include('_header.php'); ?>

<?php
// error_log(__ROOT__);
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
// error_reporting(E_ERROR | E_PARSE);

set_time_limit ( 5 );

$user_name = $_SESSION ['user_name'];
$user_email = $_SESSION ['user_email'];
$user = UserFactory::getUser ( $user_name, $user_email );
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}
$devices = $user->getDevices ();
$boxes = $user->getBoxes ();
$client = new Aws ();
if (count ( $devices ) == 0) {
    echo "</br>";
    echo "&nbsp;&nbsp;&nbsp;<emp><i> You need to add  devices to your account. Buy them from <a href='https://www.amazon.in/Camera-storage-Temperature-Humidity-sensors/dp/B0787VLB3X'>here</a></i> </emp>";
    echo "</br></br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a target='_blank' href='http://ibeyonde.com/cloudcam-setup/'>CloudCam and CloudBell setup manual</a></b>";
    echo "</br></br>";
    echo "<a href='http://ibeyonde.com/cloudcam-setup/'><img src='http://ibeyonde.com/wp-content/uploads/2017/02/cloudcam_parts.001.jpeg'></a>";
    include ('_footer.php');
    exit ( 0 );
} 

$remoteip = urldecode($_SERVER['REMOTE_ADDR']); 
$loc=TEMP_DASH;
?>



<body>
 
<main>
<div class="container-fluid">
    <div class="album py-5 bg-none">
        <div class="container-fluid">

            <div class="row">
                 <?php
                    foreach ( $devices as $device ) {
                        if (strcmp ( $device->box_name, $thisbox ) !== 0) {
                            continue;
                        }
                        ?>
                            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                               
                                <div class="card mb-4 box-shadow">
                                   <div class="card-image">
            
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <iframe class="embed-responsive-item"
                                            src="/views/graph/temp.php?uuid=<?php echo $device->uuid; ?>&timezone=<?php echo $device->timezone; ?>"></iframe>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                
                            <div class="card-body">
                            
                                <div class="d-flex justify-content-center">
                                <small class="text-muted"><?php echo $device->device_name; ?>( <?php echo $device->uuid; ?> )</small>
                                    </div>
                        
                            </div>
                        
                   
                            </div>
                  <?php
                    }
                  ?>
            </div>
            </div>

        </div>
</main>
       
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

 
    
<?php include('common/add_space.php'); ?>
    
<?php include('common/box_bar.php'); ?>
    
<?php include('_footer.php'); ?>
