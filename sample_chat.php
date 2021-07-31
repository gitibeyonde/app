<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- <link rel="shortcut icon" href="img/favicon.png"> -->

<link href='//fonts.googleapis.com/css?family=Lato:300,400,700'
	rel='stylesheet'>

<!-- Syntax Highlighter -->
<link rel="stylesheet" type="text/css"
	href="docs/syntax-highlighter/styles/shCore.css" media="all">
<link rel="stylesheet" type="text/css"
	href="docs/syntax-highlighter/styles/shThemeDefault.css" media="all">

  <link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
<!-- Normalize/Reset CSS-->
<link rel="stylesheet" href="docs/css/normalize.min.css">
<!-- Main CSS-->
<link rel="stylesheet" href="docs/css/main.css">
  <!--Ibeyonde -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-173989963-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-173989963-1');
    </script>
<style>
body {
	font-family: Arial, Helvetica, sans-serif;
}

* {
	box-sizing: border-box;
}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button1 {
	background-color: cornflowerblue;
	color: white;
	padding: 16px 20px;
	border: none;
	cursor: pointer;
	opacity: 0.8;
	position: fixed;
	bottom: 23px;
	left: 28px;
	width: 280px;
}

/* The popup chat - hidden by default */
.chat-popup1 {
    background-color: white;
	display: none;
	position: fixed;
	bottom: 0;
	left: 15px;
	border: 3px solid #f1f1f1;
	z-index: 9;
}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
	background-color: cornflowerblue;
	color: white;
	padding: 16px 20px;
	border: none;
	cursor: pointer;
	opacity: 0.8;
	position: fixed;
	bottom: 23px;
	right: 28px;
	width: 280px;
}

/* The popup chat - hidden by default */
.chat-popup {
    background-color: white;
	display: none;
	position: fixed;
	bottom: 0;
	right: 15px;
	border: 3px solid #f1f1f1;
	z-index: 9;
}

/* Add styles to the form container */
.form-container {
	max-width: 300px;
	padding: 10px;
	background-color: aliceblue;
}

/* Full-width textarea */
.form-container textarea {
	width: 100%;
	padding: 15px;
	margin: 5px 0 22px 0;
	border: none;
	background: #f1f1f1;
	resize: none;
	min-height: 200px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
	background-color: #ddd;
	outline: none;
}

/* Set a style for the submit/send button */
.form-container .btn {
	background-color: #4CAF50;
	color: white;
	padding: 16px 20px;
	border: none;
	cursor: pointer;
	width: 100%;
	margin-bottom: 10px;
	opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
	background-color: coral;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
	opacity: 1;
}
</style>
</head>
<?php
if (!$_GET['chat'] || !$_GET['tchat']){
    die;
}

$chat = $_GET['chat'];
$tchat = $_GET['tchat'];

?>
<body style="background-color: ivory;" id="welcome">
  <div class="container">

	<section id="welcome">
		<div class="content-header">
			<h1>App Chat</h1>
		</div>
		<div class="welcome">
			<h2 class="twenty">This demo page shows how you can publish the
				App as chat on your webisite.</h2>
			<p>There are two chat buttons on the page. The one on the left is optimized for text chat the other one is more of a form like chat. 
			If you designed the chat as a text chat that is minimal images and not lot of complicated inputs then use the one on the left otherwise use the rigth one.
			The text chat will usually contains direction on what response is expected from the user. For some of the demo apps that is not the case.
			This page is not optimized for smaller screens.</p>
			<br/>
			<h5>You can make use of following css, js and html to make similar embedding on your webpage.</h5>
		</div>
	</section>

	<section id="welcome">
		<h2 class="title">CSS</h2>

		<pre class="brush:css">
           < style >
            body {
            	font-family: Arial, Helvetica, sans-serif;
            }
            
            * {
            	box-sizing: border-box;
            }
            
            /* Button used to open the chat form - fixed at the bottom of the page */
            .open-button {
            	background-color: #555;
            	color: white;
            	padding: 16px 20px;
            	border: none;
            	cursor: pointer;
            	opacity: 0.8;
            	position: fixed;
            	bottom: 23px;
            	right: 28px;
            	width: 280px;
            }
            
            /* The popup chat - hidden by default */
            .chat-popup {
            	display: none;
            	position: fixed;
            	bottom: 0;
            	right: 15px;
            	border: 3px solid #f1f1f1;
            	z-index: 9;
            }
            
            /* Add styles to the form container */
            .form-container {
            	max-width: 300px;
            	padding: 10px;
            	background-color: white;
            }
            
            /* Full-width textarea */
            .form-container textarea {
            	width: 100%;
            	padding: 15px;
            	margin: 5px 0 22px 0;
            	border: none;
            	background: #f1f1f1;
            	resize: none;
            	min-height: 200px;
            }
            
            /* When the textarea gets focus, do something */
            .form-container textarea:focus {
            	background-color: #ddd;
            	outline: none;
            }
            
            /* Set a style for the submit/send button */
            .form-container .btn {
            	background-color: #4CAF50;
            	color: white;
            	padding: 16px 20px;
            	border: none;
            	cursor: pointer;
            	width: 100%;
            	margin-bottom: 10px;
            	opacity: 0.8;
            }
            
            /* Add a red background color to the cancel button */
            .form-container .cancel {
            	background-color: red;
            }
            
            /* Add some hover effects to buttons */
            .form-container .btn:hover, .open-button:hover {
            	opacity: 1;
            }
            < /style >
		
		</pre>
	</section>



	<section>
		<h2 class="title">HTML (for formy chat)</h2>
		<p>
			
		</p>
		<pre class="brush:html">
          < button class="open-button" onclick="openForm()">Chat< /button>
            < div class="chat-popup" id="myForm">
              < iframe src="<?php echo $chat; ?>" class="embed-responsive-item"
                scrolling="yes" frameborder="0" width="100%" height="600">< /iframe>
              < form action="#" class="form-container">
                   < button type="button" class="btn cancel" onclick="closeForm()">Close< /button>
               < /form>
          < /div >
		</pre>
	</section>
	
	

	<section>
		<h2 class="title">HTML (for texty chat)</h2>
		<p>
			
		</p>
		<pre class="brush:html">
          < button class="open-button" onclick="openForm()">Chat< /button>
            < div class="chat-popup" id="myForm">
              < iframe src="<?php echo $tchat; ?>" class="embed-responsive-item"
                scrolling="yes" frameborder="0" width="100%" height="600">< /iframe>
              < form action="#" class="form-container">
                   < button type="button" class="btn cancel" onclick="closeForm()">Close< /button>
               < /form>
          < /div >
		</pre>
	</section>
	

	<section>
		<h2 class="title">Javascript</h2>
		<p>
			
		</p>
		<pre class="brush:js">
         < script >
            function openForm() {
              document.getElementById("myForm").style.display = "block";
            }
            
            function closeForm() {
              document.getElementById("myForm").style.display = "none";
            }
         < /script >
		</pre>
	</section>

	<br/>
	<br/>
	<br/>
	<br/>
	
	<button class="open-button1" onclick="openForm1()">Text Chat</button>
	<div class="chat-popup1" id="myForm1">
		<iframe src="<?php echo $tchat; ?>" class="embed-responsive-item"
			scrolling="yes" frameborder="0" width="100%" height="600"></iframe>
		<form action="#" class="form-container">
			<button type="button" class="btn cancel" onclick="closeForm1()">Close</button>
		</form>
	</div>
	
	<button class="open-button" onclick="openForm()">Formy Chat</button>
	<div class="chat-popup" id="myForm">
		<iframe src="<?php echo $chat; ?>" class="embed-responsive-item"
			scrolling="yes" frameborder="0" width="100%" height="600"></iframe>
		<form action="#" class="form-container">
			<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
		</form>
	</div>
</div>
<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}


function openForm1() {
  document.getElementById("myForm1").style.display = "block";
}

function closeForm1() {
  document.getElementById("myForm1").style.display = "none";
}
</script>

</body>
<!-- Essential JavaScript Libraries
		==============================================-->
<script type="text/javascript" src="docs/js/jquery.nav.js"></script>
<script type="text/javascript" src="syntax-highlighter/scripts/shCore.js"></script> 
<script type="text/javascript" src="syntax-highlighter/scripts/shBrushXml.js"></script> 
        <script type="text/javascript" src="syntax-highlighter/scripts/shBrushCss.js"></script> 
        <script type="text/javascript" src="syntax-highlighter/scripts/shBrushJScript.js"></script> 
<script type="text/javascript"
<script type="text/javascript">
            SyntaxHighlighter.all()
        </script>
<script type="text/javascript" src="docs/js/custom.js"></script>
  <script src="/vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="/vendors/bootstrap/bootstrap.bundle.min.js"></script>
</html>

