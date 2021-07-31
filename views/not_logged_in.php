<?php
include (__ROOT__ .'/views/_header.php');

if ($_SERVER['SERVER_NAME'] == "app.ibeyonde.com") {
    require_once (__ROOT__ . '/views/app/not_logged_in.php');
} else {
    require_once (__ROOT__ . '/html/not_logged_in.php');
}

include (__ROOT__ .'/views/_footer.php');
?>
