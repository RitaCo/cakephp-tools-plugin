<?php
App::uses('DispatcherFilter', 'Routing');
App::uses('CakeSession', 'Model/Datasource');
include App::pluginPath('RitaTools').'Vendor'.DS.'ua-parser'.DS.'uaparser.php';


class FontDispatcher extends DispatcherFilter {
	
	public $priority = 10;
	
	private $fonts = array( 'ttf','woff','eot','svg');


	/**
	 * FontDispatcher::beforeDispatch()
	 * 
	 * @param mixed $event
	 * @return
	 */
	public function beforeDispatch(CakeEvent $event) {

		$count = CakeSession::check('download')? CakeSession::read('download') : 0;
		
		if($count > Configure::read('Font.limit')){
			die(':P You Should be dissuaded from do so.');
		}
		
		$url = urldecode($event->data['request']->url);
		
		if ($url === false){
			return ;
		}
		
		$urlPrams = pathinfo($url);
		$ext = isset($urlPrams['extension'])? $urlPrams['extension'] : false; 
		
		
		if ( !$ext  and !in_array( $ext, $this->fonts)) {
			return null ;	
		}
		$isForbidden = false;				
				
		
		
	

		$refere =  $event->data['request']->referer(); 
		
		if ( !preg_match('/(lab.lritaco.[net|ir]|lab.ritaco.[net|ir])/',$refere)){
			$isForbidden = true;
		} 
	//		l($refere,'r : '.$isForbidden);	
	
		 $parser = new UAParser();
		 $result = $parser->parse(env('HTTP_USER_AGENT'));
			
			
			

		if ( $isForbidden === false  and empty($result->toFullString) ){
			
			$isForbidden = true;
		}

		
		$response = $event->data['response'];
		
		if ($isForbidden) {
//			l('isstopped','flagh');
			$count++;
			 CakeSession::write('download',$count);
			$event->stopPropagation();
			//$response->location('/booom');
			//$response->statusCode(404);
			$response->header(array('Location' => Router::url('/booom', true)));
			$response->statusCode(302);
			return $response;
		
		}else{
			
			$assetFile = App::pluginPath('Webfonts').DS.'Vendor'.DS.'fonts'.DS.$urlPrams['basename'];
		//$response->body($assetFile);
			$this->_deliverAsset($response, $assetFile, $ext);
			return $response;
		}
	}


	/**
	 * FontDispatcher::_deliverAsset()
	 * 
	 * @param mixed $response
	 * @param mixed $assetFile
	 * @param mixed $ext
	 * @return void
	 */
	protected function _deliverAsset(CakeResponse $response, $assetFile, $ext) {
		ob_start();
		$compressionEnabled = Configure::read('Asset.compress') && $response->compress();
		if ($response->type($ext) == $ext) {
			$contentType = 'application/octet-stream';
			$agent = env('HTTP_USER_AGENT');
			if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent) || preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent)) {
				$contentType = 'application/octetstream';
			}
			$response->type($contentType);
		}
		if (!$compressionEnabled) {
			$response->header('Content-Length', filesize($assetFile));
		}
	//	$response->cache(filemtime($assetFile));
		$response->send();
		ob_clean();
		if ($ext === 'css' || $ext === 'js') {
			include $assetFile;
		} else {
			readfile($assetFile);
		}

		if ($compressionEnabled) {
			ob_end_flush();
		}
	}

}


