<?php
App::uses('SessionComponent', 'Controller/Component');

class RitaSessionComponent extends SessionComponent {


	public function initialize(Controller $controller) {
		if (!$controller->request->is('requested')) {
			CakeSession::write('Alerts',array());
		}
		
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
	private function _alert( $type, $msg  ) {
			$messages = CakeSession::read('Alerts');
		if ($messages === null){
			$messages = array();
		}
		
		$messages[] = array('type' => $type , 'msg' => $msg );
		CakeSession::write('Alerts',$messages);
			
		
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
		
		$this->setFlash($msg , $global, 'error',$key);
	}


	public function alertInfo($msg) {
		$this->_alert('info',$msg);
	}

	public function alertSuccess($msg) {
		$this->_alert('success',$msg);
	}

	public function alertError($msg) {
		$this->_alert('error',$msg);
	}

	public function alertWarning($msg) {
		$this->_alert('warning',$msg);
	}

	public function __call($name, $args) {
		// TODO:  write dynamic flash message
	 	throw new BadMethodCallException("Method '{$name}' does not exist.");	
	}
	
}