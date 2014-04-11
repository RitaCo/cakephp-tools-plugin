<?php
App::uses('LibRepo','RitaTools.Lib');
class RequireHelper extends AppHelper {
	
	
	/**
	 * RequireHelper::js()
	 * 
	 * @param mixed $name
	 * @param bool $cdn
	 * @return void
	 */
	public function js($name,$version = false) {
		$cdn = false;
		if ($name[0] === '>') {
			$name[0] = '';
			$name = trim($name);
			$cdn = true;		
		}
		if(strpos($name, '::')=== false){
			$name .='::';
		} 
		list($name,$ver) = explode('::',$name); 
		
		
		
		$name = call_user_func(RitaTools::libRepo($name),$cdn, $name[1]);
	
		pr($name);	
	
		//	return LibRepo::$name(RitaTools::inLocalhost(),$version);	
	}



	
}