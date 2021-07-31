<?php
define ( '__ROOT__',  ( dirname ( __FILE__ )));

include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/SmsWfUtils.php');

$_SESSION['log'] = new Log('debug');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Future of Apps - Mobile App, Mobile Web, Websites ?</title>
<meta property="og:image" content="/html/blogs/img/file_manager.png" />
<meta name="og:title" property="og:title" content="Future of Apps - Mobile App, Mobile Web, Websites ?">
<meta name="description" content="Future of Apps - Mobile App, Mobile Web, Websites: Both Mobile Apps and mobile web are here to stay" />
<meta name="author" content="Boris" />
  <link rel="icon" href="/img/favicon.ico" type="image/png">
  <link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="/vendors/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body id="page-top">
    <div class="container">
       <?php echo SmsWfUtils::getPublicAppList(); ?>
        </br>
        </br>
        <h6>Ibeyonde.com platform provides tools to build and host the Apps. 
        The platform also provides Text and Email messaging services to market and to keep your customers engaged.</h6>
    
    </div> 
  <!-- ================ End footer Area ================= -->
  <script src="/vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="/vendors/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
