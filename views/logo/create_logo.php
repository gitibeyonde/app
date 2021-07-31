<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Cabin|Archivo+Narrow|Play|Pacifico|Roboto+Condensed|Montserrat|Noto+Sans|Lobster|Fjalla+One|Josefin+Sans|Signika|Ubuntu+Condensed|Maven+Pro|Exo+2|Karla|Exo|Dancing+Script|Righteous|Chewy|Courgette' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/sms_header.css">
</head>

<body>
<div class="container">
                <h4>Generate Logo</h4>

                <div class="row">
                    <div class="col" width="200px">
                        <div id="logo-output">
                            <h1><span id="icon-output"><i class="fa fa-gears"></i></span></h1>
                        </div>
                    </div>
                </div>

                    <form action="#">

                        <div class="row">
                            <div class="col-4">
                                <label for="logo-title">Logo Name: </label>
                                <input type="text" name="logo-title"  maxlength="10" class="form-control" value="">
                            </div>
                            <div class="col-4">
                                <label for="font">Font: </label><br>
                                <select  name="font" class="form-control" style="font-family: 'FontAwesome';font-weight: 900;">
                                    <?php include_once __ROOT__.'/views/utils/fonts.php'; ?>
                                </select>
                           </div>
                            <div class="col-4">
                                <label for="icon">Icon: </label><br>
                                <select id="icon" name="icon" class="form-control" style="font-family:'FontAwesome';font-weight: 900;">
                                    <?php include_once __ROOT__.'/views/utils/icons.php'; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                              <label for="base-color">Base Color: </label>
                                <select id="base-color" name="base-color" class="form-control">
                                    <?php include __ROOT__.'/views/utils/colors.php'; ?>
                                </select>
                            </div>

                        <div class="col-4">
                              <label for="secondary-color">Secondary Color: </label>
                                <select id="secondary-color" name="secondary-color" class="form-control">
                                    <?php include __ROOT__.'/views/utils/colors.php'; ?>
                                </select>
                        </div>

                        <div class="col-3">
                              <label for="icon-color">Icon Color: </label>
                                <select id="icon-color" name="icon-color" class="form-control">
                                    <?php include __ROOT__.'/views/utils/colors.php'; ?>
                                </select>
                        </div>
                    </div>

                    </form>

                <div class="row">
                    <div class="col-12">
                        <button id="preview" type="submit" value="customise_img_add" class="btn btn-block btn-sim4">Upload Generated Logo</button>
                        <form id="downloader" action="/index.php?view=wiz_wf_desc&bot_id=<?php echo $bot_id; ?>&submit=add-logo"  method="post" enctype="multipart/form-data">
                            <input type="hidden" name="img_val" id="img_val" value="" />
                            <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                            <input type="hidden" name="name" value="logo">
                        </form>
                    </div>
               </div>
</div>
<script  type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script src="/views/logo/public/js/html2canvas.js"></script>
<script src="/views/logo/public/js/app.js"></script>
</body>
</html>
