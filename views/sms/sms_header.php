 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
<title><?php echo (isset($page_title) ?  $page_title : 'Delta Apps building and hosting platform'); ?></title>
<meta property="og:url" content="https://ibeyonde.com" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo (isset($page_title) ?  $page_title : 'Delta Apps For Business'); ?>" />
<meta property="og:image" content="<?php echo (isset($page_image) ? 'https://simonline.in'.$page_image : 'https://simonline.in/img/delta_app_feature.png'); ?>" />
<meta name="description" content="<?php echo (isset($page_desc) ? $page_desc : 'Delta Apps for e-commerce, data collection, catalogue and marketing surveys.'); ?>" />
<link rel="icon" href="/img/favicon.ico" type="image/png">


<link  rel="stylesheet" href="/css/jquery-ui.css">
<link rel="stylesheet" href="/vendors/themify-icons/themify-icons.css">
<link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/sms_header.css">

<script  type="text/javascript" src="/vendors/jquery/jquery-3.3.1.min.js"></script>
<script  type="text/javascript" src="/js/jquery-ui.js"></script>
<script  type="text/javascript" src="/js/jquery.ajaxchimp.min.js"></script>
<script type="text/javascript" src="/vendors/bootstrap5/js/bootstrap.min.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-173989963-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-173989963-2');
</script>


</head>
<body>
<?php
if (isset ( $_GET ['view'] )) {
    $view = $_GET ['view'];
} else {
    $view = MAIN_VIEW;
}
$_SESSION['view'] = $view;
?>

  <!--================Header Menu Area =================-->
  <header class="header_area">
    <div class="main_menu">
      <nav class="navbar navbar-expand-lg navbar-light">
         <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <a class="navbar-brand" href="/index.html"></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
           <?php if (isset ( $_SESSION ['user_name'] )) {
                        $login = new Login ();
        		  ?>
               <ul class="nav navbar-nav menu_nav justify-content-end">
                  <?php
                  if ( in_array ( $view, array (MAIN_VIEW, WORKFLOW_LISTING, WIZ_WF_CATEGORY, WIZ_WF_DESC, WIZ_WF_LOGO, WIZ_WF_IMAGES, WIZ_WF_KB,
                          WIZ_WF_KB_ADD, WIZ_WF_KB_TABLE, WIZ_WF_PUBLISH
                  ))) { ?>
                		<li class="nav-item active"><a class="nav-link" href="/index.php?view=<?php echo WORKFLOW_LISTING ?>">Catalogs</a></li>
                 <?php } else { ?>
                		<li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo WORKFLOW_LISTING ?>">Catalogs</a></li>
                  <?php } ?>


                  <?php if (in_array($view, array(MESSAGE_TEMPLATE, SMS_TRIGGER, SMS_SURVEY, DELIVER_MICROAPP, SMS_AUDIENCE, AUDIENCE_DB_TABLE) ) ){ ?>
            		  <li class="nav-item active "><a class="nav-link" href="/index.php?view=<?php echo DELIVER_MICROAPP ?>">Messaging</a></li>
                  <?php } else { ?>
            		<li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo DELIVER_MICROAPP ?>">Messaging</a></li>
                  <?php } ?>



                  <?php if (in_array($view,  array(USER_ACCOUNT, USER_USAGE, USER_BILLING, USER_CHARGE, USER_HOST_KEY) ) ){ ?>
            		  <li class="nav-item active "><a class="nav-link" href="/index.php?view=<?php echo USER_ACCOUNT ?>">Account</a></li>
                  <?php } else { ?>
            		<li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo USER_ACCOUNT ?>">Account</a></li>
                  <?php } ?>


                  <?php if (in_array($view,  array(FORUM_LISTING, FORUM_CREATE_TOPIC, FORUM_TOPIC) ) ){ ?>
            		  <li class="nav-item active "><a class="nav-link" href="/index.php?view=<?php echo FORUM_LISTING ?>"></a></li>
                  <?php } else { ?>
            		<li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo FORUM_LISTING ?>"></a></li>
                  <?php } ?>

                  <li class="nav-item"><a class="nav-link" href="/index.php?view=<?php echo LOGOUT_VIEW ?>">
                    	<i class="ti-power-off btn-logout"></i>
                    	<font class="txt-name"><?php echo $_SESSION ['user_name']; ?></font></a></li>
                </ul>
          <?php
            } else {
                ?>
            <ul class="nav navbar-nav menu_nav justify-content-end">
              <li class="nav-item active"><a class="nav-link" href="/index.html">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="/catalog-maker/catalog-features">Features</a></li>
              <li class="nav-item"><a class="nav-link" href="/catalog-maker/catalog-demos">Demos</a></li>
              <li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>
            </ul>
            <ul class="navbar-right">
              <li class="nav-item">
                <a class="button button-header bg" href="/register.php">Sign up</a>
              </li>
            </ul>

          <?php } ?>
          </div>
       </nav>
    </div>
  </header>
<script>
function pop_up(url){
    window.open(url,'win2','status=no,toolbar=no,scrollbars=no,titlebar=no,menubar=no,resizable=yes,width=1060,height=700,directories=no,location=no');
    return false;
}
</script>