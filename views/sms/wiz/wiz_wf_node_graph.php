 <?php
 if ($submit != "add"){
     try {
         $wfl = new WfLayout($user_id, $bot_id);

         $wfl->assignCoordinates();
         list($nodes, $edges, list($minx, $miny), list($maxx, $maxy), $fo) = $wfl->getSubsetFor($state);
         $mult_factor = 100;

         $x_max = $maxx - $minx + 2;
         $y_max = $maxy - $miny + 2;
         $view_height = ($fo+1)*$mult_factor;
     }
     catch (Throwable $e){
         $_SESSION['message'] = "Node not saved, there is no acton in newly added node.";
         error_log($e);
         return;
     }
?>

<div class="container" id="svgcontainer" style="border: 1;height: <?php echo $view_height > 400 ? 400 : $view_height; ?>px;width: 100%;overflow: auto;scrollbar-width: none;">
<svg width="<?php echo $x_max*$mult_factor; ?>" height="<?php echo $y_max*$mult_factor; ?>" >
<?php
        try {
            foreach($edges as $edge){
                $end = $wfl->getCoordinate($edge[1]);
                if ($end[0] == -1)continue;
                $start = $wfl->getCoordinate($edge[0]);
                $sx = ($start[0]-$minx + 1) *$mult_factor ;
                $sy =  ($start[1] -$miny + 1)*$mult_factor;
                $ex = ($end[0] - $minx + 1) *$mult_factor;
                $ey =  ($end[1] - $miny +1)*$mult_factor;

                if ($sx > $ex){
                    echo '<line x1="'.$sx .'" y1="'. $sy .'" x2="'. $ex .'" y2="'. $ey .'" style="opacity: 0.6;stroke: grey;stroke-width: 2;transform: translateY(-2px);" />';
                }
                else {
                    echo '<line x1="'.$sx .'" y1="'. $sy .'" x2="'. $ex .'" y2="'. $ey .'" style="opacity: 0.6;stroke: orange;stroke-width: 2;transform: translateY(2px);" />';
                }
            }
        }
        catch (Exception $e){
            $_SESSION['message'] = $e->getMessage();
        }
        foreach($nodes as $node){
            if ($node->getState() == 'start'){
                //skip
                continue;
            }
            list($node_x, $node_y) = $node->getCoordinate();
            $x = ($node_x - $minx + 1)*$mult_factor;
            $y = ($node_y - $miny +1 )*$mult_factor;
            $tstate=$node->getState();
            $len = strlen($tstate);
            $x_text = $x - $len * 6;
            $y_text = $y - 10;
            if ($tstate == $state){
                echo '<a href="/index.php?view='.$view.'&bot_id='.$bot_id.'&state='.$tstate.'&submit=edit">';
                echo '<circle cx="'. $x .'" cy="'. $y .'" r="10" stroke="green" stroke-width="5" fill="orange" />';
                echo '<text x="'. $x_text .'" y="'. $y_text .'" fill="blue">'. $tstate .'</text>';
            }
            else {
                echo '<a href="/index.php?view='.$view.'&bot_id='.$bot_id.'&state='.$tstate.'&submit=edit">';
                echo '<circle cx="'. $x .'" cy="'. $y .'" r="10" stroke="green" stroke-width="5" fill="green" />';
                echo '<text x="'. $x_text .'" y="'. $y_text .'" fill="blue">'. $tstate .'</text>';
            }
            echo '</a>';
        }
?>
</svg>
</div>
<?php } ?>
