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
    href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
    rel="stylesheet">
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

</head>
<body data-spy="scroll" data-target="#mySidenav1">
    <nav class="navbar navbar-inverse" id="header">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#myNavbar">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                        class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/admin/index.php"><img src="/img/ilogo.png" width="104" align="top"/></a>
            </div>


 <div class="collapse navbar-collapse" id="myNavbar">
     <?php if (isset($_SESSION['user_name'])) {?>
     <ul class="nav navbar-nav">
                    <li><a href="/admin/index.php?view=<?php echo ADMIN_MAIN_VIEW ?>">Dashboard</a></li>
                    <li><a href="/admin/index.php?view=<?php echo ADMIN_USAGE ?>">Usage</a></li>
                    <li><a href="/admin/edit.php"><span class="glyphicon glyphicon-user"></span> &nbsp;<?php echo $_SESSION['user_name'] ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/admin/index.php?logout=true"> <span
                            class="glyphicon glyphicon-log-out"> </span></a></li>
                </ul>
      <?php } ?>
  </div>
        </div>
        </div>
    </nav>
   