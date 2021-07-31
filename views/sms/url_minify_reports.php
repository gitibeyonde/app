<?php

$user_id = $_SESSION['user_id'];

$od_details = $od->getAccess($id_details);
$maps = $od->getMapList($user_id);
?>
  
<div class="container">          
<br/>       
 <h3>Mappings Listing</h3>
  <br/>
               
   <table  class="table table-striped">
      <tr>
       <th>Mini</th>
       <th>Url </th>
       <th>Hits</th>
       <th>Details</th>
      </tr>
   
    <?php
    if (count($maps) == 0) {
        echo "No mappings found, create one !";
    } else {
   ?> 
   <?php    foreach ($maps as $map) { ?>
    <tr>
        <td>1do.in/<?php echo $map['id']; ?></td>
        <td class="break"><?php echo $map['url']; ?></td>
        <td><?php echo $od->logHits($map['id']); ?></td>
        <td>
         <form action="/index.php?view=url_minify" method="post">
               <input type=hidden name=id value="<?php echo $map['id']; ?>">
               <button name="submit" type="submit" value="detailed_report" class="btn btn-sim1">Report</button>
          </form>
        </td>
    </tr>
    <?php 
    error_log("id_details=$id_details , id=".$map['id'].", OD=".print_r($od_details, true));
        if ($id_details == $map['id']) { ?>
      <tr><td></td><td colspan=2>
      <table  class="table table-striped">
          <tr>
           <th>Ip</th>
           <th>Timestamp</th>
          </tr>
        <?php  foreach ($od_details as $odd) { ?>
          <tr>
          <td><?php echo $odd["ip"]; ?>
          <td><?php echo $odd["createdOn"]; ?>
          </td>
          </tr>
        <?php }  ?>
       </table>
       </td><td></td></tr>
       <?php  }  } } ?>
    </table>
</div>

        
