<!DOCTYPE html>
<html lang="en">
<head>
<title>IbeyondE</title>
<link rel="icon" type="ico" href="/img/favicon.ico">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="/css/app_header.css">


<!-- https://material.io/resources/icons/?style=baseline -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons"
      rel="stylesheet">

<!-- https://material.io/resources/icons/?style=outline -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined"
      rel="stylesheet">

<!-- https://material.io/resources/icons/?style=round -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round"
      rel="stylesheet">

<!-- https://material.io/resources/icons/?style=sharp -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons+Sharp"
      rel="stylesheet">

<!-- https://material.io/resources/icons/?style=twotone -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons+Two+Tone"
	rel="stylesheet">

</head>
<?php
$logo_img = "/img/ico192.png";

if (isset($_GET['view'])){
    $view = $_GET['view'];
}
else {
    $view = "main_view";
}
?>
<header>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container-fluid">
        <a class="navbar-brand" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default"><img src="<?php echo $logo_img;?>" width="80px"></a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php if (isset($_SESSION['user_name'])) {
                $login = new Login();
                ?>
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default" data-bs-toggle="tooltip" data-bs-placement="bottom" title="History"><?php if ($view == MAIN_VIEW ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>image_search</span></h1>";} 
                            else { echo "<h1><span class='material-icons-outlined md-48 var(--logoBlue)'>image_search</span></h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo LIVE_DASH ?>&box=default" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Realtime"><?php if ($view == LIVE_DASH ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>ondemand_video</span></h1>";} 
                        else { echo "<h1><span class='material-icons-outlined md-48  var(--logoBlue)'>ondemand_video</span></h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo TAGS ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tags"><?php if ($view == TAGS ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>label</span></h1>";} 
                        else { echo "<h1><span class='material-icons-outlined md-48  var(--logoBlue)'>label</span></h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo ALERT_DASH ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notifications"><?php if ($view == ALERT_DASH ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>notifications_active</span></h1>";} 
                        else { echo "<h1><span class='material-icons-outlined md-48  var(--logoBlue)'>notifications_active</span></strike></h1>"; }?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?view=<?php echo USAGE_DASH ?>"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Usage"><?php if ($view == USAGE_DASH ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>data_usage</span></h1>";} 
                        else { echo "<h1><span class='material-icons-outlined md-48  var(--logoBlue)'>data_usage</span></h1>"; }?></a>
                    </li>
            </ul>

            <!-- div class="hidden-xs" -->
            <ul class="d-flex" style="list-style-type: none;">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo USER_ACCOUNT ?>"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Account"><?php if ($view == USER_ACCOUNT ) { echo "<h1 class='sel'><span class='material-icons-two-tone md-48  var(--logoBlue)'>account_circle</span></h1>";} 
                    else { echo "<h1><span class='material-icons-outlined md-48  var(--logoBlue)'>account_circle</span></h1>"; }?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo LOGOUT_VIEW ?>"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Logout"><span class="material-icons md-48 red">logout</span></a></li>
            </ul>
         <?php } ?>
          </div>
        </div>
   </nav>
 </header>

