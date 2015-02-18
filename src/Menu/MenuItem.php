<?php
namespace Rita\Tools\Menu;

class MenuItem extends \ArrayObject{

  //  protected $item = null;
//    
//    protected $childs =null;
    
    public function __construct( $items , $childs )
    {
        parent::__construct([
            'item' => $items,
            'childs' => $childs
        
        ],1);

      
        
    }

    
//    public function __construct($array = [])
//    {
//        parent::__construct($array,1);
//    }
//    
    
    
    public function render(){
        return 'aa';
    }
    
    
    public function __toString(){
        return 'aa';
    }
}