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
$logo_img = "/img/logo_light.png";

if (isset($_GET['view'])){
    $view = $_GET['view'];
}
else {
    $view = MAIN_VIEW;
}
?>
    <header>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container-fluid">
        <a class="navbar-brand" href="/index.php?view=<?php echo MAIN_VIEW ?>&box=default"><img src="<?php echo $logo_img;?>" width="200"></a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent" style="padding-top: 30px;padding-left: 50px;">
            <?php if (isset($_SESSION['user_name'])) {
                $login = new Login();
                ?>
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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

                        <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php?view=<?php echo ALERT_DASH ?>"><?php if ($view == ALERT_DASH ) { echo "<h1 class='sel'>Alerts</h1>";} else { echo "<h1>Alerts</strike></h1>"; }?></a>
                        </li>

                        <?php } ?>

            </ul>

            <!-- div class="hidden-xs" -->
            <ul class="d-flex" style="list-style-type: none;">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo USAGE_DASH ?>"><?php if ($view == USAGE_DASH ) { echo "<h1 class='sel'>Usage</h1>";} else { echo "<h1>Usage</h1>"; }?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?view=<?php echo LOGOUT_VIEW ?>"><h1><span class="material-icons md-48 red">logout</span></h1></a></li>
            </ul>
         <?php } ?>
          </div>
        </div>
   </nav>
 </header>


