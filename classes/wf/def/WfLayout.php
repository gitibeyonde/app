<?php

include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');

class WfLayout {


    private $bid = null;
    private $log = null;
    private $nd=array();
    private $processed_states=array();
    private $edges= array();
    private $fo=2;
    private $len=2;
    private $xytaken = array();

    public function __construct($uid, $bid){
        $this->log=isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $this->bid = $bid;

        $wfdb = new WfMasterDb();
        $nodes = $wfdb->getNodes($bid);
        if($nodes != null){
            $x=1;
            $y=1;
            foreach($nodes as $node){
                $nn = new SmsWfNode($uid, $bid, "911111111111", $node['state'], $node['message'], explode("\n", $node['actions']), $node['help']);
                $this->nd[$node['state']] = $nn;
                $this->nd[$node['state']]->setCoordinate($x++, $y);
                $this->log->trace("(".$x. ", ".$y. "), ".$this->nd[$node['state']]->getState());
            }
        }
    }

    public function getNodes(){
        return $this->nd;
    }

    public function getNode($state){
        return $this->nd[$state];
    }
    public function getEdges(){
        return $this->edges;
    }

    public function getStartNode(){
        return $this->nd['start'];
    }

    public function getCoordinate($node_name){
        if (in_array($node_name, $this->processed_states)){
            $node = $this->nd[$node_name];
            return $node->getCoordinate();
        }
        else {
            return array(-1, -1);
        }
    }

    public function assignCoordinates(){
        $x=1;
        $y=1;
        $this->_assignCoordinates($this->getStartNode(), $x, $y);
        $this->log->debug("Fanout=".$this->fo." Len=".$this->len);
    }


    private function _assignCoordinates(SmsWfNode $start_node, $x, $y){
        list($x, $y) = $this->checkIfTaken($x, $y);
        $start_node->setCoordinate($x, $y);
        $this->log->trace("(".$x. ", ".$y. "), ".$start_node->getState());
        $this->processed_states[]=($cur_state = $start_node->getState());
        $new_states = $start_node->getNextStates();
        $x += 2;
        if ($this->len < $x){
            $this->len = $x;
        }
        foreach($new_states as $new_state){
            $this->edges[] = array($cur_state, $new_state);
            $this->log->trace("Allocating Edge=".$cur_state."--".$new_state);
            if (!in_array($new_state, $this->processed_states)){
                if (array_key_exists($new_state, $this->nd)){
                    $new_node = $this->nd[$new_state];
                    $this->_assignCoordinates($new_node, $x, $y);
                    $y += 1;
                    if ($this->fo < $y){
                        $this->fo = $y;
                    }
                }
            }
        }
    }
    private function checkIfTaken($x, $y){
        $coord=array ($x, $y);
        foreach($this->xytaken as $xy){
            if ($x==$xy[0] && $y==$xy[1]){
                $y += 1.5;
                $x += 1.5;
                $coord = array ($x, $y);
                break;
            }
        }
        $this->xytaken[] = $coord;
        return $coord;
    }
    public function fanout(){
        return $this->fo;
    }

    public function length(){
        return $this->len;
    }

    public function getSubsetFor($node_state){
        $minx = $miny = 0;
        $maxy = $maxx = 0;
        $sub_graph = array();
        $edges = array();
        $this_node = $this->nd[$node_state];
        list($minx, $miny) = list($maxx, $maxy) = $this_node->getCoordinate();
        if ($this_node == null){
            throw new Exception("Unknown node ".$node_state);
        }
        $sub_graph[] = $this_node;
        $ns = $this_node->getNextStates();
        $in_count=0;
        foreach ($ns as $next){
            if (!array_key_exists($next, $this->nd))continue;
            $next_node = $this->nd[$next];
            $sub_graph[] = $this->nd[$next];
            $in_count++;
            $edges[] = array($node_state, $next);
            list($x, $y) = $next_node->getCoordinate();
            if ($maxx < $x) $maxx = $x;
            if ($maxy < $y) $maxy = $y;
        }
        //get previous nodes
        $out_count=0;
        foreach ($this->nd as $node){
            $ns = $node->getNextStates();
            foreach ($ns as $next){
                if ($next == $node_state){
                    $sub_graph[] = $node;
                    $out_count++;
                    $edges[] = array($node->getState(), $node_state);
                    list($x, $y) = $node->getCoordinate();
                    if ($minx > $x) $minx = $x;
                    if ($miny > $y) $miny = $y;
                }
            }
        }
        return array($sub_graph, $edges, array($minx, $miny), array($maxx, $maxy), $out_count > $in_count ? $out_count : $in_count);
    }

    private function _fanout(){
        //get maximum fan out
        $fo=0;
        $all_states=array();
        foreach($this->nd as $n){
            $ss = $n->getNextStates();
            if ($fo<count($ss)){
                $fo = count($ss);
            }
        }
        return $fo*8;
    }



}


?>