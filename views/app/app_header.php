<!DOCTYPE html>
<html lang="en">
<head>
<title>IbeyondE</title>
<link rel="icon" type="ico" href="/img/favicon.ico">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<script src="/js/f882775bc0.js" crossorigin="anonymous"></script>
<script  type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script  type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script  type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script  type="text/javascript" src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link href="//code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="/vendors/themify-icons/themify-icons.css">
<link rel="stylesheet" href="//www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/app_header.css">

</head>
<?php 
$logo_img = "/img/logo_light.png";

if (isset($_GET['view'])){
    $view = $_GET['view'];
}
else {
    $view = MAIN_VIEW;
}
?>
    <header>
    <nav class="navbar fixed-top navbar-expand-md navbar-dark">
        <a class="navbar-brand" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default"><img src="<?php echo $logo_img;?>" width="230"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (isset($_SESSION['user_name'])) {
                $login = new Login();
                ?>
            <ul class="navbar-nav leftul">
                 <?php if (isset($_SESSION['capability'])) {
                     if( strpos($_SESSION['capability'], 'CAMERA') !== false) {
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default"><?php if ($view == MAIN_VIEW ) { echo "<h1 class='sel'>Motion</h1>";} else { echo "<h1>Motion</h1>"; }?></a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo LIVE_DASH ?>&box=default"><?php if ($view == LIVE_DASH ) { echo "<h1 class='sel'>Live</h1>";} else { echo "<h1>Live</h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo TAGS ?>"><?php if ($view == TAGS ) { echo "<h1 class='sel'>Tags</h1>";} else { echo "<h1>Tags</h1>"; }?></a>
                    </li>
                        <?php if ($_SESSION['role'] == 'SUBS' || $_SESSION['role'] == 'ADMIN') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?view=<?php echo ALERT_DASH ?>"><?php if ($view == ALERT_DASH ) { echo "<h1 class='sel'>Alerts</h1>";} else { echo "<h1>Alerts</h1>"; }?></a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="/index.php?view=<?php echo ANALYTICS ?>"><?php if ($view == ANALYTICS ) { echo "<h1 class='sel'>Events</h1>";} else { echo "<h1>Events</h1>"; }?></a>
                            </li>
                        
                        <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php?view=<?php echo ALERT_DASH ?>"><?php if ($view == ALERT_DASH ) { echo "<h1 class='sel'>Alerts</h1>";} else { echo "<h1>Alerts</strike></h1>"; }?></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo ANALYTICS ?>"><?php if ($view == ANALYTICS ) { echo "<h1 class='sel'>Events</h1>";} else { echo "<h1>Events</h1>"; }?></a>
                        </li>
                        <?php } ?>
                    <?php }
                    if( strpos($_SESSION['capability'], 'SIM') !== false && $_SERVER['SERVER_NAME'] == "app.ibeyonde.com") {
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default"><?php if ($view == MAIN_VIEW ) { echo "<h1 class='sel'>GSM</h1>";} else { echo "<h1>GSM</h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo ADMIN_INTENT ?>&box=default"><?php if ($view == ADMIN_INTENT ) { echo "<h1 class='sel'>Intents</h1>";} else { echo "<h1>Intents</h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo ADMIN_WORKFLOW ?>&box=default"><?php if ($view == ADMIN_WORKFLOW ) { echo "<h1 class='sel'>Workflow</h1>";} else { echo "<h1>Workflow</h1>"; }?></a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo ADMIN_CHATDB ?>&box=default"><?php if ($view == ADMIN_CHATDB ) { echo "<h1 class='sel'>ChatDB</h1>";} else { echo "<h1>ChatDB</h1>"; }?></a>
                    </li>
                    <?php }
                    if( strpos($_SESSION['capability'], 'TEMPERATURE') !== false) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo TEMP_DASH ?>&box=default"><?php if ($view == TEMP_DASH ) { echo "<h1 class='sel'>Temp</h1>";} else { echo "<h1>Temp</h1>"; }?></a>
                    </li>
                    <?php } ?> 
                <li class="nav-item"><a class="nav-link" href="/edit.php?view="><h1><span class="glyphicon glyphicon-user"></span> &nbsp;<?php echo $_SESSION['user_name'] ?></h1></a></li>
                <?php }?>
            </ul>
           
            <!-- div class="hidden-xs" -->
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo USAGE_DASH ?>"><?php if ($view == USAGE_DASH ) { echo "<h1 class='sel'>Usage</h1>";} else { echo "<h1>Usage</h1>"; }?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo LOGOUT_VIEW ?>"><h1><i class="fas fa-1x fa-sign-out-alt"></i></h1></a></li>
            </ul>
            <?php } ?>
        </div>
   </nav>
 </header>
   

    



