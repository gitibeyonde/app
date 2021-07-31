<?php 
include_once(__ROOT__ . '/views/_header.php'); 
include_once(__ROOT__ . '/classes/sms/SmsIntent.php');
include_once(__ROOT__ . '/classes/sms/SmsBotParams.php'); 


$int = new SmsIntent();
error_log("Intent Post is = " . isset($_POST['submit']));
$action=null;
if (isset($_POST['submit'])){
    $action = $_POST['submit'];
    error_log("Action is " . $action);
    if ($action == "add"){
        $intent=$_POST['intent'];
        $definition = $_POST['definition'];
        $type=$_POST['type'];
        $int->createIntentN($intent, $type, $definition);
    }
    else if ($action == "delete"){
        $intent=$_POST['intent'];
        $int->deleteIntentN($intent);
    }
    else if ($action == "edit"){
        $intent=$_POST['intent'];
        $definition = $int->getIntentDefinitionN($intent);
        error_log("Def=".$definition);
    }
    else if ($action == "save"){
        $intent=$_POST['intent'];
        $definition=$_POST['definition'];
        $int->updateIntentN($intent, $definition);
    }
}
$intents = $int->getIntentsN();
?>

<body>
<main>
<div class="container">
    
    <h3>Intents</h3>
    
    <hr/>
       
  <form class="form-inline"  action="/index.php?view=<?php echo ADMIN_INTENT; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <div class="col-xs-3 col-sm-2">
      <label for="name">Name:</label>
    </div>
    <div class="col-xs-3">
      <input type="text" class="form-control" id="intent" placeholder="Intent Name" name="intent" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
     </div>
   </div>
   <div class="form-group">
    <div class="col-xs-3 col-sm-2">
      <label for="name">Type:</label>
    </div>
    <div class="col-xs-3">
      <input type="text" class="form-control" id="type" name="type" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
     </div>
   </div>
    
   <div class="form-group row flex-v-center">
    <div class="col-xs-3 col-sm-2">
      <label for="desc">Definition:</label>
    </div>
     <div class="col-xs-3">
        <textarea class="form-control" rows="8" cols="80" name="definition" id="definition" placeholder="Enter Definitions" required></textarea>
       <div id="charNum"></div>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
     </div>
   </div>
  <button type="submit" name="submit" value="add" class="btn btn-primary"><i class="fas fa-plus"></i></button>
   </form>
   

    <hr/>
            
 <h5>Intent Listing</h5>
     <table class="table table-striped table-hover">
            <?php
            if ($intents == null || count($intents) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No Inents found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Name</th>
           <th>Type</th>
           <th>Definition</th>
           <th>Edit</th>
           <th>Bin</th>
           </tr>
           <?php    foreach ($intents as $map) { 
               if ($action == "edit" && $intent == $map['intent']){
               ?>
           <tr>
            <form class="form-inline" action="/index.php?view=<?php echo ADMIN_INTENT; ?>"  method="post" enctype="multipart/form-data">
                <td><?php echo $intent; ?></td>
                <td><?php echo $map['type']; ?></td>
                <td colspan=2>
                    <input type=hidden name=intent value=<?php echo $intent; ?>>
                    <textarea class="form-control" rows="10" cols="80" name="definition" 
                     placeholder="Enter Definitions" required><?php echo $definition; ?></textarea>
                </td>
                  <td>
                    <button name="submit" type="submit" value="save"  class="btn btn-info"><i class="fas fa-check"></i></button>
                  </td>
            </form>
           </tr>
            <?php } else { ?>
            <tr>
            <td><?php echo $map['intent']; ?></td>
            <td><?php echo $map['type']; ?></td>
            <td><small><?php echo substr($map['definition'], 0, 60); ?></small></td>
              <td>
                <form class="form-inline" action="/index.php?view=<?php echo ADMIN_INTENT; ?>"  method="post">
                <input type=hidden name=intent value=<?php echo $map['intent']; ?>>
                <button name="submit" type="submit" value="edit"  class="btn btn-info"><i class="fas fa-edit"></i></button>
                </form>
              </td>
              <td>
                <form class="form-inline" action="/index.php?view=<?php echo ADMIN_INTENT; ?>"  method="post">
                <input type=hidden name=intent value=<?php echo $map['intent']; ?>>
                <button name="submit" type="submit" value="delete"  class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
        <?php } } }?>
        </table>   
    
    
 
 
 </div>
</main>
<?php include(__ROOT__ . '/views/_footer.php'); ?>
</body>
