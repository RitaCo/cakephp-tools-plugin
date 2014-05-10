<?php

class RitaValidatesBehavior	extends ModelBehavior {
	

	public $_domains;
	
		
/**
 * RitaValidatesBehavior::setup()
 * 
 * @param mixed $model
 * @param mixed $settings
 * @return void
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias] = array_merge($this->settings,$settings);
		
	}	
	
/**
 * RitaValidatesBehavior::depoEmail()
 * 
 * @param mixed $Model
 * @param mixed $value
 * @return
 */
	public function depoEmail(Model $Model, $value){
		$value = current($value);
		list(,$value) = explode('@', $value);
		
		$domains = $this->_getDomains();

		
		RETURN !array_search($value,$domains);
	}
	
/**
 * RitaValidatesBehavior::confrim()
 * 
 * @param mixed $model
 * @param mixed $value
 * @param mixed $val
 * @return
 */
	public function confrim(Model $model, $value, $val) {
		$value = current($value);
		$val = Hash::get($model->data,$val);
		return ($value === $val);
	}




/**
 * RitaValidatesBehavior::_getDomains()
 * 
 * @return
 */
	private function _getDomains(){
		if (empty($this->_domains)) {
			$domains = file_get_contents(RITATOOLS_DIR . 'Vendor' . DS . 'sde');
			$this->_domains = explode("\r\n",$domains);
		}
		
		return $this->_domains;
	}
	
	

		
}