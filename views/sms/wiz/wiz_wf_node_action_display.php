
   <?php

   if (isset($action_index) && $action_index == $count && $submit == "edit_action") { ?>
        <tr>
        <td><h4>Edit </h4></td>
         <td colspan="6"><h5><?php echo $count; ?> &emsp;&emsp;  <?php echo ucfirst($action_name); ?></h5></td>
        </tr>

        <input type="hidden" name="action_name[<?php echo $count; ?>]" value="<?php echo $action_name; ?>">
        <tr>
         <td colspan="8">
          <?php if ($action_name == "intent") { ?> // If intent
           <div class="form-control" id="intent<?php echo $count; ?>">
             <label><h3>Select Intent</h3></label>
                <input type="text" name="action_desc[<?php echo $count; ?>]" value="<?php echo $action_string; ?>" list="intent_list">
                <datalist id="intent_list">
                <?php foreach($Lintents as $int) { ?>
                  echo "<option>".$int. "</option>";
                <?php } ?>
            </div>
          <?php }
          else if ($action_name == "search" || $action_name == "extract" || $action_name == "choices") {
          ?>
            <div class="form-control" id="desc<?php echo $count; ?>">
             <label><h3>Action Description</h3></label>
                <input type="text" name="action_desc[<?php echo $count; ?>]" value="<?php echo $action_string; ?>">
            </div>
          <?php } ?>

            <?php if ($action_name == "choices" || $action_name == "intent") { ?>
            <div class="form-control" id="extract<?php echo $count; ?>">
             <label><h3>Extraction Choice</h3></label>
                <input type="text" Placeholder="choices extraction"  name="choice_extract[<?php echo $count; ?>]" value="<?php echo $extract; ?>">
            </div>
            <?php } ?>

        </td>
      </tr>

     <tr>
        <td colspan="8">
        <div class="form-control" id="nextstate<?php echo $count; ?>">
         <label><h3>Next State</h3></label>
            <input type="text" name="next_state[<?php echo $count; ?>]" list="next_state"
                                           value="<?php echo $next_state; ?>">
            <datalist id="next_state">
            <?php
                $nodes = $WFDB->getNodes($bot_id);
                foreach ($nodes as $node){
                    echo "<option>".$node['state']. "</option>";
                }
                ?>
            </datalist>
        </div>
       </td>
     </tr>
     <tr>
       <td colspan="2">
       </td>
        <td colspan="2">
            <button type="submit" name="submit" value="update"  class="btn btn-sim1"
                        onclick="onClick('workflow_action', '<?php echo $count; ?>');">Update</button>

       </td>
       <td colspan="2">
       </td>
        <td colspan="2">
            <button type="submit" name="submit" value="cancel"   class="btn btn-sim1"
                        onclick="onClick('workflow_action', '<?php echo $count; ?>');">Cancel</button>

       </td>
     </tr>
   <?php } else { // EDIT ACTION ELSE
      if ($action_name == "choices") {?>
       <tr>
         <td>Choose</td> <td> <?php echo $extract; ?></td> <td><?php echo $action_string; ?></td><td><?php echo $next_state; ?></td>

       <?php } else if ($action_name == "extract") {?>
       <tr>
        <td>Input</td> <td> <?php echo $extract; ?></td> <td></td> <td><?php echo $next_state; ?></td>

       <?php } else if ($action_name == "unmatched") {?>
       <tr>
         <td>To Next State</td> <td></td> <td> </td> <td><?php echo $next_state; ?></td>

       <?php } else if ($action_name == "upload") {?>
       <tr>
         <td>Upload</td> <td></td> <td> <?php echo $action_string; ?></td> <td><?php echo $next_state; ?></td>

       <?php } else if ($action_name == "search") {?>
       <tr>
         <td>Button</td><td></td> <td> <?php echo $action_string; ?></td> <td><?php echo $next_state; ?></td>

       <?php } ?>
       <td><button type="submit" name="submit" value="edit_action"  class="btn btn-sim1"
                onclick="onClick('workflow_action', '<?php echo $count; ?>');"><?php echo $Icons->get("pencil", 1.5, "green"); ?></button></td>
         <td><button type="submit" name="submit" value="delete_action"  class="btn btn-sim2"
                onclick="onClick('workflow_action', '<?php echo $count; ?>');"><?php echo $Icons->get("trash_can", 1.5, "red"); ?></button></td>
       </tr>

        <input type="hidden" name="action_name[<?php echo $count; ?>]" value="<?php echo $action_name; ?>">
        <input type="hidden" name="action_desc[<?php echo $count; ?>]" value="<?php echo $action_string; ?>">
        <input type="hidden" name="choice_extract[<?php echo $count; ?>]" value="<?php echo $extract; ?>">
        <input type="hidden" name="next_state[<?php echo $count; ?>]" value="<?php echo $next_state; ?>">
   <?php }?>
