<?php
define ( '__ROOT__', dirname(dirname ( dirname ( __FILE__ ))));
require_once(__ROOT__.'/classes/sms/SmsUtils.php');
include(__ROOT__.'/views/_header.php');

?>
  
<body>


<main class="mt-5" role="main">  
   
<div class="container">
    
    <div class="row">
        <br /> <br /> <br /> <br />
    </div>
    
<?php 
error_log(file_get_contents( 'php://input' ));
error_log(print_r(apache_request_headers(), true));
#error_log(print_r($_SERVER, true));
error_log(print_r($_POST, true));
error_log(print_r($_GET, true));
error_log(print_r($_FILES, true));
?>
 
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
 
</div>
</main>
<?php include(__ROOT__.'/views/_footer.php'); ?>