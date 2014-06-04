<?php

/**
 * PersianBehavior
 * این رفتار کننده جهت فیلتر کردن تمامی داده های فارسی برای ذخیره سازی صحیح در دیتابیس می باشد.
 * @package RitaTools.Behavior
 * @author Mohammad Saleh Souzanchi <Saleh.soozanchi@gmail.com>
 * @copyright RitaCo 2014
 * @version 0.0.1
 * @access public
 */
class PersianBehavior extends ModelBehavior{
    
   private  $runtime = array(); 
/**
 * PersianBehavior::setup()
 * 
 * @param mixed $model
 * @param mixed $FixFields
 * @return void
 */
   	public function setup(Model $model, $config = array()) {
      if (!isset($this->settings[$model->alias])){
      	$this->settings[$model->alias] = array();
      }
	  $this->settings[$model->alias] = $config+$this->settings[$model->alias];
	  
	  $this->runtime[$model->alias]['fields'] = $model->getColumnTypes();   
    }

/**
 * PersianBehavior::beforeValidate()
 * 
 * @param mixed $model
 * @param mixed $options
 * @return
 */
    public function beforeValidate(Model $model, $options = array()) {
		$this->runtime[$model->alias]['data'] = $model->data[$model->alias];
      	$this->_fixPresian($model);
      return true;
    }


/**
 * PersianBehavior::afterValidate()
 * 
 * @param mixed $model
 * @return
 */
	public function afterValidate(Model $model) {
		if (!empty($model->validationErrors)){
			$model->data[$model->alias] = $this->runtime[$model->alias]['data'];
		}
		
		return true;
	}


/**
 * PersianFixBehavior::beforeSave()
 * 
 * @param mixed $model
 * @param mixed $options
 * @return
 */
    public function beforeaSave(Model $model, $options = array()) {
        if ( !$this->__Field_IS_String() )  return false; 
        return true;
     }  
    



	private function _fixPresian(Model $model) {
		foreach($model->data[$model->alias] as $field => $value){
			$model->data[$model->alias][$field] = $this->_fixField($model->alias, $field, $value);
		}
	}    
    
    
	private function _fixField($model, $field , $value) {
		if(empty($value)){
			return $value;
		}
		
		if(isset($this->runtime[$model]['fields'][$field])) {
			$type = $this->runtime[$model]['fields'][$field];
		} else {
			$type = gettype($value);
		}
		
		switch ($type) {
			case 'string':
			case 'text':
				return $this->_convertPersian4DBText($value);	
		}
		
		
		return $value;
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
 * PersianBehavior::_convertPersian4DBText()
 * 
 * @param mixed $text
 * @return
 */
	protected function _convertPersian4DBText($text){
		$text = \Persian\Convertor::arabic2persian($text);
		$text = \Persian\Convertor::persian2englishNumbers($text);
		return $text;
	}



/**
 * PersianBehavior::persianString()
 *  persianString is validator rule for persian string
 * @param mixed $model
 * @param mixed $value
 * @param mixed $options
 * @return
 */
	public function persianAlpha(Model $model, $value) {
		$value = current($value);
	
		if (is_string($value) && preg_match("/^[\p{Arabic}\x{200C}\x{200D}\s\-]+$/u", $value)) {
			return true;
		}
		return false;
	}
	
/**
 * PersianBehavior::persianAlphaNumeric()
 * persianAlphaNumeric is validator rule for persian string
 * @param mixed $model
 * @param mixed $value
 * @return
 */
	public function persianAlphaNumeric(Model $model, $value) {
		$value = current($value);
		
		if (is_string($value) && preg_match("/^[\p{Arabic}\x{200C}\x{200D}0-9\s\-]+$/u", $value)) {
			return true;
		}
		return false;
	}
	
}