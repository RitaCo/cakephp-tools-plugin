<?php

class PersianFixBehavior extends ModelBehavior{
    
    var $FixFields = null;
    var $Model = null;
    var $StringType = array('string','text');
    
    function setup(Model $model , $FixFields = array() )
    {
        
        $this->Model = $model;
        if ( !empty( $FixFields ) )
          $this->FixFields = $FixFields;
        else
        {
          $FixFields = $model->getColumnTypes();
          foreach ( $FixFields as $Field => $type )  
            if ( !in_array( $type , $this->StringType ) )
               unset( $FixFields[$Field]);   
          $this->FixFields = array_keys( $FixFields );
        } 
          
      
    }

    function beforeValidate(Model $model) {
      if ( !$this->__Field_IS_String() )  return false; 
      return true;
    }


    function beforeSave(Model $model){
        if ( !$this->__Field_IS_String() )  return false; 
        return true;
     }  
    
    
    
    function __Field_IS_String(){

        foreach( $this->FixFields as $Field)
            if ( !in_array( $this->Model->getColumnType( $Field ) , $this->StringType )  ){
              trigger_error("Field {$Field} in not string type  in modal {$this->Model->alias}", E_USER_WARNING);
              return false;
            }
            else
            if ( isset($this->Model->data[$this->Model->name][$Field]) and 
                 !is_array($this->Model->data[$this->Model->name][$Field]) )
               $this->Model->data[$this->Model->name][$Field] = $this->__fixPersianString($this->Model->data[$this->Model->name][$Field]); 

            
        return true;  
    }



    function __fixPersianString($text){
       
       if(is_null($text))
          return null;
       $replacePairs = array(
                chr(0xD9).chr(0xA0) => chr(0xDB).chr(0xB0),
                chr(0xD9).chr(0xA1) => chr(0xDB).chr(0xB1),
                chr(0xD9).chr(0xA2) => chr(0xDB).chr(0xB2),
                chr(0xD9).chr(0xA3) => chr(0xDB).chr(0xB3),
                chr(0xD9).chr(0xA4) => chr(0xDB).chr(0xB4),
                chr(0xD9).chr(0xA5) => chr(0xDB).chr(0xB5),
                chr(0xD9).chr(0xA6) => chr(0xDB).chr(0xB6),
                chr(0xD9).chr(0xA7) => chr(0xDB).chr(0xB7),
                chr(0xD9).chr(0xA8) => chr(0xDB).chr(0xB8),
                chr(0xD9).chr(0xA9) => chr(0xDB).chr(0xB9),
                chr(0xD9).chr(0x83) => chr(0xDA).chr(0xA9),
                chr(0xD9).chr(0x89) => chr(0xDB).chr(0x8C),
                chr(0xD9).chr(0x8A) => chr(0xDB).chr(0x8C),
                chr(0xDB).chr(0x80) => chr(0xD9).chr(0x87) . chr(0xD9).chr(0x94));
       return strtr($text, $replacePairs);
   }    
    
}