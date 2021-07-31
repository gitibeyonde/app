<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
require_once (__SMSMROOT__ . '/classes/Utils.php');
require_once (__SMSMROOT__ . '/classes/sms/SmsUtils.php');
require_once (__SMSMROOT__ . '/classes/sms/SmsMinify.php');
require_once (__SMSMROOT__ . '/classes/sms/SmsBotParams.php');


include(__ROOT__.'/views/_header.php');

$user_id = $_SESSION['user_id'];

$utils= new Utils();
$od = new SmsMinify();

error_log("Survey Post is = " . isset($_POST['submit']));
$action=null;
$id_details=null;
if (isset($_POST['submit'])){
    $action = $_POST['submit'];
    if ($action == "add"){
        $count = $od->getUrlCount($user_id);
        if ($count <= SmsBotParams::$max_minified_url){
            $url = $_POST['url'];
            if (filter_var($url, FILTER_VALIDATE_URL)){
                $utils->setMessage("");
                $od->createMap($url, $user_id);
                $utils->setMessage("");
            }
            else {
                $utils->setMessage("The url is invalid [".$url."]");
            }
        }
    }
    else if ($action=="disable"){
        $url_id = $_POST['url_id'];
        $od->deleteUrl($user_id, $url_id);
    }
    else if ($action=="edit"){
        $url = $_POST['url'];
        $url_id = $_POST['url_id'];
        $od->updateUrl($user_id, $url_id, $url);
    }
    else if ($action=="detailed_report"){
        $id_details = $_POST['id'];
    }
}

$maps=$od->getMapList($user_id);
?>
    <script>
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }

</script>

<body>
    <main>

    <div class="container">
        
        <div class="tab">
          <button class="tablinks" onclick="openTab(event, 'Manage')"  id="defaultOpen"><h2>Manage</h2></button>
          <button class="tablinks" onclick="openTab(event, 'Reports')" id="reports"><h2>Reports</h2></button>
        </div>
            
        <!-- Tab content -->
        <div id="Manage" class="tabcontent">
          <?php include (__SMSMROOT__ . '/views/sms/url_minify_manage.php'); ?>
        </div>
        
        <div id="Reports" class="tabcontent">
          <?php include (__SMSMROOT__ . '/views/sms/url_minify_reports.php'); ?>
        </div>
        
    </div>
    </main>
    <script>
        <?php if (strpos($action, "detailed_report") !== False) { ?>
            document.getElementById("reports").click();
        <?php }else  { ?>
            document.getElementById("defaultOpen").click();
        <?php } ?>
    </script>
<?php include(__SMSMROOT__.'/views/_footer.php'); ?>
