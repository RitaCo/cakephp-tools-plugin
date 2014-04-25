<?php

class PersianBehavior extends ModelBehavior{
    
    public $FixFields = null;
    public $Model = null;
    public $StringType = array('string','text');
    
/**
 * PersianBehavior::setup()
 * 
 * @param mixed $model
 * @param mixed $FixFields
 * @return void
 */
    public function setup(Model $model , $FixFields = array() ) {
        
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

/**
 * PersianBehavior::beforeValidate()
 * 
 * @param mixed $model
 * @param mixed $options
 * @return
 */
    public function beforeValidate(Model $model, $options = array()) {
      if ( !$this->__Field_IS_String() )  return false; 
      return true;
    }


/**
 * PersianFixBehavior::beforeSave()
 * 
 * @param mixed $model
 * @param mixed $options
 * @return
 */
    public function beforeSave(Model $model, $options = array()) {
        if ( !$this->__Field_IS_String() )  return false; 
        return true;
     }  
    
    
    
/**
 * PersianBehavior::__Field_IS_String()
 * 
 * @return
 */
    protected function __Field_IS_String(){

        foreach ($this->FixFields as $Field) {
            if (!in_array( $this->Model->getColumnType( $Field ) , $this->StringType )) {
              trigger_error("Field {$Field} in not string type  in modal {$this->Model->alias}", E_USER_WARNING);
              return false;
            } elseif ( isset($this->Model->data[$this->Model->name][$Field]) and !is_array($this->Model->data[$this->Model->name][$Field])) {
               $this->Model->data[$this->Model->name][$Field] = $this->__fixPersianString($this->Model->data[$this->Model->name][$Field]);
			}
		}
		return true;  
    }

/**
* PersianBehavior::__fixPersianString()
* 
* @param mixed $text
* @return
*/
   protected function __fixPersianString($text){
       
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

/**
 * PersianBehavior::arabic2persian()
 * 
 * @param mixed $string
 * @return
 */
	public function arabic2persian($string) {
  		$arabicCharacters = array("ي","ك","‍","دِ","بِ","زِ","ذِ","ِشِ","ِسِ","‌","ى","ة");
  		$persianCharacters = array("ی","ک","","د","ب","ز","ذ","ش","س","","ی","ه");
  		return str_replace($arabicCharacters,$persianCharacters,$String);
 	}
    
}