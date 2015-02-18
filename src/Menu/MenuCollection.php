<?php
namespace Rita\Tools\Menu;
use \Cake\Utility\Inflector;
use Cake\Utility\Hash;
use Rita\Tools\Menu\MenuItem;
use Rita\Tools\Menu\MenuChildContainer;
class MenuCollection
{
    public $_named = [];
        
    protected $_items = [];
    
    
    
    
    
    /**
     * MenuCollection::addItem()
     * 
     * @param mixed $name
     * @param mixed $item
     * @return
     */
    public function addMenu($name, $item = [])
    {
        $name = strtolower($name);
        $name = str_replace('.','.childs.',$name);    
        $item = new MenuLink($item);
        
        
        $this->_named  = Hash::insert($this->_named,$name,[
            'item' => $item,
            'childs' => []
        ]);
                
                           
       //$this->_named[$name]['_item'] = $this->__insertItem($item);;
        
        return $this;
    }
    
    
    
    /**
     * MenuCollection::addChild()
     * 
     * @param mixed $name
     * @return void
     */
    public function addChild($name,$item = [])
    {
       $name = strtolower($name);
       $name = str_replace('.','.childs.',$name);
       $name = $name.'.childs'; 
      
        $childs = Hash::get($this->_named, $name);
      
       
        $childs[] = (new MenuLink($item));
       
        
        Hash::insert($this->_named,$name, $childs );
        return $this;       
             
    }
    
    
    
    private function __insertItem($item)
    {
        $this->_items[] = $item;
        end($this->_items);
        return key($this->_items);  
    } 
        
}
