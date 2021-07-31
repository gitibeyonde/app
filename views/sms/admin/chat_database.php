<?php 
include_once(__ROOT__ . '/views/_header.php'); 
include_once(__ROOT__. '/classes/chat_ai/ChatDb.php');
include_once(__ROOT__ .'/classes/core/Log.php');

$_SESSION['log'] = new Log("info");

$cdb = new ChatDb();
$action=null;
if (isset($_GET['action'])){
    $action = $_GET['action'];
    error_log("Action is " . $action);
    if ($action == "add"){
        $text = $_GET['text'];
        $cdb->saveText($tags, $texts);
    }
    else if ($action == "delete"){
        $id=$_GET['id'];
        $cdb->deleteText($id);
    }
    else if ($action == "edit"){
        $id=$_GET['id'];
        $text = $cdb->getText($id);
    }
}
$texts = $cdb->getTexts();
?>

<body>
<main>
<div class="container">
    
    <h3>Texts</h3>
    
    <hr/>
       
  <form action="/index.php?view=<?php echo ADMIN_CHATDB; ?>" method="get">
    <div class="form-group">
      <label for="name">Tags:</label>
      <input type="text" class="form-control" id="tags" size="80" placeholder="Tags" name="tags" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
   </div>
    
   <div class="form-group row flex-v-center">
      <label for="desc">Text:</label>
        <textarea class="form-control" rows="8" cols="80" name="text" id="text" placeholder="Text" required></textarea>
       <div id="charNum"></div>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
   </div>
  <button class="form-control"  type="submit" name="action" value="add" class="btn btn-primary"><i class="fas fa-plus"></i></button>
   </form>
   

    <hr/>
            
 <h5>Texts</h5>
     <table class="table table-striped table-hover">
            <?php
            if ($intents == null || count($intents) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No Inents found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Tags</th>
           <th>Text</th>
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
                    <button name="action" type="submit" value="save"  class="btn btn-info"><i class="fas fa-check"></i></button>
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
                <button name="action" type="action" value="edit"  class="btn btn-info"><i class="fas fa-edit"></i></button>
                </form>
              </td>
              <td>
                <form class="form-inline" action="/index.php?view=<?php echo ADMIN_INTENT; ?>"  method="post">
                <input type=hidden name=intent value=<?php echo $map['intent']; ?>>
                <button name="action" type="submit" value="delete"  class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
        <?php } } }?>
        </table>   
    
    
 
 
 </div>
</main>
<?php include(__ROOT__ . '/views/_footer.php'); ?>
</body>
