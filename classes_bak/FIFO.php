<?php 

class FIFO {
    
    private $queue = array();
    private $size = 10;
    private $ind = 0;
    
    public function __construct($size)
    {
        $this->size = $size;
        $this->kick();
        $this->butt();
    }
    
    public function queue($item){
        for ($i=$this->size-1;$i>0;$i--){
            $this->queue[$i] = $this->queue[$i-1];
        }
        $this->queue[0] = $item;
    }
    
    public function get($i){
        return $this->queue[$i];
    }
    
    public function kick(){
        for ($i=0;$i<$this->size/2;$i++){
            $this->queue[$i] = 0;
        }
    }
    
    public function butt(){
        for ($i=$this->size/2;$i<$this->size;$i++){
            $this->queue[$i] = 1;
        }
    }
    
    public function reliability(){
        $rel = 0;
        $total = 0;
        for ($i=0;$i<$this->size;$i++){
            $total = $total + 1;
            $item = $this->queue[$i];
            $rel = $rel + $item;
        }
        $rel_per = ($rel * 100)/$total;
        //error_log("reliability: ".$rel_per);
        return $rel_per;
    }
    
}


?>