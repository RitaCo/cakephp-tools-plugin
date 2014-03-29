<?php
App::uses('SessionHelper', 'View/Helper');

class RitaSessionHelper extends SessionHelper {
	
	public $helpers = array('Html');
	
	public function flash($key = 'flash', $attrs = array()) {
		return	parent::flash($key,$attrs);
	}
	
/**
 * RitaSessionHelper::afterRender()
 * 
 * @param mixed $filename
 * @return void
 */
	public function afterRender($filename) {
		$msgs = CakeSession::read('Alerts');
		if(empty($msgs)){
			return;
		}
		$out = array();
		$out[]= '<script type="text/javascript">';
		foreach($msgs as $msg){

			$out[] = sprintf('alert.%s("%s");',$msg['type'],$msg['msg']);
		}
		$out[]= '</script>';
		$out = implode(' ',$out);
		
		$this->getView()->append('content',$out);
	}
	
}