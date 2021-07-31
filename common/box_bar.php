


<?php 

//error_log("Boxes=".count($boxes));
if (count($boxes) > 1) { ?>

<div class="row navbar-fixed-bottom">
<div class="table-responsive">
<table class="table">
<tr class="active">
<td>
&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
  <?php if ($loc == MAIN_VIEW) { ?>
    <form class="form-horizontal" name=createBox method=GET action="index.php">
        <input type="hidden" name="view" value="main_view" />
        <input type="hidden" name="box" value="<?php echo $thisbox; ?>" />
        <?php if ($animate=='true'){
            echo '<input type="hidden" name="animate" value="false" />';
            echo '<input class="btn btn-link" type="submit" type=submit name="submit" value="Show Last Motion" />';
        }
        else {
            echo '<input type="hidden" name="animate" value="true" />';
            echo '<input class="btn btn-link" type="submit" type=submit name="submit" value="Animate Latest Motion" />';
        }
    }
   ?>
    </form>
      </td>
         <td>
            <code><b>Now Showing: &nbsp;&nbsp;<?php echo $thisbox; ?>,<b></code> 
         </td>
         <td> 
             <b>&nbsp;&nbsp; Goto : </b>
         </td>
            <?php foreach ($boxes as $box){ 
                if (strcmp ( $box, $thisbox ) !== 0) {
            ?>
                <td align="left">
                    <a href="/index.php?view=<?php echo $loc; ?>&box=<?php echo $box; ?>"><b><?php echo $box;?></b></a>
                </td>
            <?php 
                }
            } ?>
      </tr>
    </table>
   </div>
   </div>
<?php } ?>
