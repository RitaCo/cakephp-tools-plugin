<?php
App::uses('SessionComponent', 'Controller/Component');

class RitaSessionComponent extends SessionComponent {


	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}
	
	
	
	public function setFlash($message, $element = 'RitaTools.flash/global', $params = array(), $key = 'flash') {
		$params['class'] = 'error';
		parent::setFlash($message,$element,$params,$key);
	}


/**
 * RitaSessionComponent::_flash()
 * 
 * @param mixed $msg
 * @param bool $global
 * @param mixed $class
 * @return void
 */
	private function _alert( $type, $msg , $url  ) {
			$messages = CakeSession::read('Alerts');
		if ($messages === null){
			$messages = array();
		}
		
		$messages[] = array('type' => $type , 'msg' => $msg );
		CakeSession::write('Alerts',$messages);
		if($url){
			return $this->controller->redirect($url);
		}		
		
	//	$this->setFlash( $msg , $element , array( 'class' => $class ,'global'=> $global ) );

    }
	
	
/**
 * RitaSessionComponent::flashError()
 * 
 * @param mixed $msg
 * @param bool $global
 * @return void
 */
	public function flashError($msg, $key = 'flash') {
		
		return $this->setFlash($msg , 'RitaTools.flash/global', array(),$key);
	}


	public function alertInfo($msg , $url = false) {
		return	$this->_alert('info',$msg ,$url);
	}

	public function alertSuccess($msg, $url = false) {
		return $this->_alert('success',$msg, $url);
	}

	public function alertError($msg, $url = false) {
		return $this->_alert('error',$msg, $url);
	}

	public function alertWarning($msg, $url = false) {
		return $this->_alert('warning',$msg, $url);
	}

	public function __call($name, $args) {
		// TODO:  write dynamic flash message
	 	throw new BadMethodCallException("Method '{$name}' does not exist.");	
	}
	
}