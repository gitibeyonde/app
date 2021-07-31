<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/Utils.php');

include (__ROOT__ . '/views/_header.php');

$user_id = $_SESSION['user_id'];
$url = $_POST['url'];
$link = $_POST['link'];
$type = $_POST['type'];
$id = $_POST['id'];
$template = $_POST['template'];

$SU = new SmsUtils();
$utils = new Utils();
$sms = $utils->templateReplace($template,["otp" => rand(999, 9999), "link" => $link]);
$size=strlen($sms);
list($hk, $exp) = $SU->getHostKey($user_id);
?>

<body>


    <main class="mt-5" role="main">

    <div class="container">

        <div class="row">
            <br /> <br /> <br /> <br />
        </div>

<?php  if ($type=="otp") {?>
        <h3>The rest API appears below:</h3>
        <hr />
        <br />
        <form action="/index.php?view=MESSAGE_TEMPLATE" method="post">
            <label><font size=3 color=blue>https://simonline.in/api/otp?st=<?php echo $id; ?>&hk=<?php echo $hk;?>&ph= </font></label>
            <input type="text" size="12" name="phone" value="919999999999"> <input
                type="hidden" name="id" value=<?php $id; ?>>
            <button type="submit" name="link" value="link">Trigger Sms</button>
        </form>
        <hr/>
        <p>
            <font size=3 color=black><i><?php echo $sms; ?></i></font>
        
        </p>
            <br/>
            <?php if ( $size <= 160) {?>
                <i> <font size=2 color=green>The SMS size is ok <?php echo $size; ?></font></i>
            <?php } else {?>
                <i> <font size=3 color=red>The SMS size is in access <?php echo $size; ?></font></i>
            <?php } ?>

<?php } ?>
        <br />
        <hr />
        <div class="row">
            <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br />
        </div>

    </div>
    </main>
<?php include(__ROOT__.'/views/_footer.php'); ?>
