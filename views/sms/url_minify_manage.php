
 
<div class="container">
<?php 

require_once (__ROOT__ . '/classes/device/GsmDevice.php');
require_once (__ROOT__ . '/classes/sms/SmsBotParams.php');

$gsmdev = new GsmDevice();
$my_numbers = $gsmdev->getGsmDevice($user_id);

$count = $od->getUrlCount($user_id);
if ($count >= SmsBotParams::$max_minified_url) {
    echo " <h3>You have exceeded your quota of minified URLs. Please, get in touch with the admin @ info@ibeyonde.com</h3>";
}
else {
?>        
<br/>
 <h3>Create a new mapping</h3>
  <br/>
<form  class="form-inline" action="/index.php?view=url_minify" method="post">
    <div class="form-group">
        <label for="name">URL:&nbsp;&nbsp;</label> <input type=text class="form-control" name="url" size="84" placeholder="Paste The Url Here" required>
        <div class="valid-feedback">Valid.</div>
        <div class="invalid-feedback">Please fill out this field.</div>
    </div>
    <button type="submit" name="submit" value="add" class="btn btn-sim1">
        Add
    </button>
</form>

<?php } ?>
        
<br/>
  <hr/>
              
<br/>   
  <h3>Mappings Listing</h3>
  <br/>
       
       <table class="table table-striped">
          <tr >
           <th>Mini</th>
           <th>Url</th>
           <th>Removed On</th>
           <th>Change</th>
           <th>Unlink</th>
           <th>Check</th>
          </tr>
       
        <?php
        if (count($maps) == 0) {
            echo "No mappings found, create one !";
        } else {
       ?> 
       <?php    foreach ($maps as $map) { ?>
        <form  action="/index.php?view=url_minify" method="post">
        <tr width="100%">
        <td >1do.in/<?php echo $map['id']; ?></td>
        <td>
            <input type=text name=url size="42" value=<?php echo $map['url']; ?>>
            <input type=hidden name=url_id value="<?php echo $map['id']; ?>">
        </td>
        <td ><?php echo $map['removed']; ?></td>
        <td>
            <button name="submit" type="submit" value="edit"  class="btn btn-sim1"  data-toggle="tooltip" title="Change the linked URL">Edit</button>
          </td>
          <td>
            <button name="submit" type="submit" value="disable"  class="btn btn-sim1" data-toggle="tooltip" title="Disable the forward">UnLink</button>
        </td>
          <td>
            <a href="http://1do.in/<?php echo $map['id']; ?>" target="main"  class="btn btn-sim1"  data-toggle="tooltip" title="Open it another window.">Open</a>
        </td>
        </tr>
        </form>
        <?php } } ?>
        </table>




        <div class="row">
            <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br />
        </div>
        
</div>