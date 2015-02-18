<?php
namespace Rita\Tools\Menu;

use Rita\Tools\Menu\MenuCollection;
use Cake\Utility\Hash;

class RitaMenu
{
    
    protected static $_collection;
    
    
    
    
    public static function menu($name) {
        if(!isset(static::$_collection[$name])) {
            static::$_collection[$name] = new MenuCollection();
        }
        return static::$_collection[$name];
    }
    
    
    
    public static function render($name)
    {
        $s = static::$_collection[$name]->_named;
//        $zz = [];
//        
//        foreach ( $s as $k => $v ){
//            $zz = Hash::insert($zz,$k,$v);
//        }
      //   $zz = new \RecursiveIteratorIterator(   new \RecursiveArrayIterator($s));
         ///$zz = new \RecursiveTreeIterator(    new \RecursiveArrayIterator($zz), null, null);
      pr($s);
        
         
       
    }
    
    
 
}