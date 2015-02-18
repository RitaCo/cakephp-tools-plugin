<?php
namespace Rita\Tools\Menu;

class MenuLink 
{
    
    
        public  $title = null;
    public $url = false;
    public $icon = null;
    public $weight = 10;
    public $class = false;
    public $id = false;
    
    
    private $_prop =[
        'title' => null,
        'url' => false,
        'icon' => null,
        'weight' => 10,
        'class' => '',
        'id' => ''
    ];
    
    public function __construct( $items )
    {
       $items = $items + $this->_prop;
       
       foreach($items as $key => $val){
        $this->{$key} = $val;
       } 
        //parent::__construct($items,ARRAY_AS_PROPS);
    }
    
    
      public function render(){
        return 'aa';
    }
    
    
    public function __toString(){
        return 'link :'.$this->title;
    }
}