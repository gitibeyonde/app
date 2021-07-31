
<?php
define ( '__ROOT__', dirname ( __FILE__ ) ) ;
require_once(__ROOT__.'/classes/utils/Mobile_detect.php');

$link = $_GET['link'];
$Md = new Mobile_Detect();
?>

<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Ibeyonde - Build, Host and Launch Apps</title>
<meta property="og:image" content="/html/img/home/about.png" />
<meta name="description"
	content="Ibyonde provides tools, support, hosting and messaging for HTML 5 responsive delta Apps." />
<link rel="icon" href="/img/favicon.ico" type="image/png">
<link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
<link rel="stylesheet" href="/vendors/themify-icons/themify-icons.css">
<!-- <link rel="stylesheet" href="/vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="/vendors/owl-carousel/owl.carousel.min.css"> -->
<link rel="stylesheet" href="/css/style.css">
</head>

<?php if ($Md->isMobile()){ ?>
  <body>
  <iframe src="<?php echo $link; ?>"
					class="embed-responsive-item" scrolling="yes" frameborder="0"
					width="100%" height="100%"></iframe>
  </body>
<?php } else { ?>
<body style="background-color: ivory;" id="welcome">
	<div class="container"> 
    <div class="row" style="padding: 5vh;">
	<section id="welcome">
		<div class="content-header">
			<h1>App</h1>
		</div>
		<div class="welcome">
			<h5 class="twenty">This demo page shows how you can publish the
				App and embed it on your webpage.</h5>
		</div>
	</section>
    </div>

		<div class="row">
			<div class="col-lg-1 col-md-1 d-lg-block d-md-block d-sm-none d-none"></div>
			<div class="col-lg-4 col-md-4 col-12 col-sm-12">
				<iframe src="<?php echo $link; ?>"
					class="embed-responsive-item" scrolling="yes" frameborder="0"
					width="100%" height="600"></iframe>
			</div>
			<div class="col-lg-4 col-md-4 col-12 col-sm-12" style="text-align: center;">
			<h5>You can also invoke the app on the smartphone, by scanning the QR-code from the smartphone's camera.</h5> 
			<p style="padding: 10px; box-shadow: 2px 2px 10px lightblue;">
				<img
					src="https://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $link; ?>&sf=8&ms=r&md=0.8">
			</p>
			</div>

		</div>
	</div>
</body>

<?php } ?>
</html>
