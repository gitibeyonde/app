<?php 
define('__ROOT__', dirname(__FILE__));
include(__ROOT__.'/classes/Contact.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$log = $_SESSION['log'] = new Log('debug');

$submit = isset($_GET['submit']) ? $_GET['submit'] : "unknown";
$page = isset($_GET['page']) ? $_GET['page'] : "unknown";;
$error = "We will respond in next 24 hours.";

if ($submit == "save"){
    $name = $_GET['name'];
    $phone = $_GET['phone'];
    $email = $_GET['email'];
    $message = $_GET['message'];

    $c = new Contact();
    $c->saveContactRequest($name, $phone, $email, $page, $message);
    $error="Hi ".$name.", Thank you for reaching out to us. We will get back to you ASAP.";
}


?>
  <link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
  <link rel="stylesheet" href="/vendors/themify-icons/themify-icons.css">
  <!-- <link rel="stylesheet" href="/vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="/vendors/owl-carousel/owl.carousel.min.css"> -->
  <link rel="stylesheet" href="/css/style.css">
<div class="container">

<h3>Message Us</h3>
       
<form action="/contact.php" action="get">
 <input type="hidden" name="page" value="<?php echo $page;?>">
<div class="form-group">
    <input class="form-control" type="text" name="name" placeholder="Name*" required>
</div>
<div class="form-group">
    <input class="form-control" type="tel" name="phone"  placeholder="Phone*" required>
</div>
<div class="form-group">
    <input class="form-control" type="email" name="email" size="60" placeholder="Email*" required>
</div>
<div class="form-group"> 
    <textarea class="form-control" name="message" type="text"
   			placeholder="Accompanying message/Bug Report" value="" rows="4" cols="50" required></textarea>
</div>
<div class="form-group"> 
<button class="form-control btn btn-primary" type="submit" name="submit" value="save" style="background-color: rgba(0, 5, 255, 0.08);"><h3>Submit</h3></button>
</div>

</form>

<h4><?php echo $error; ?></h4>
</div>

  <script src="/vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="/vendors/bootstrap/bootstrap.bundle.min.js"></script>