<?php

class RitaUserAgent{
	

	protected $_report = array(
		'isValid'			=> true,
		'product'			=> null,
		'productVersion'	=> null,
		'browserName'		=> null,
		'browserVersion'	=> null,
		'osName'			=> null,
		'osVersion'			=> null,
		'platform'			=> null,
		'deviceType'		=> null,
		'deviceName'		=> null,
		'ua'				=> null,
		'extra'				=> array(),
	);
	
	public $report = array();
	public $data = null;
	
	protected $_patterns = array(
		
		'parser' => '#[\(][\w\d\s\-.\/;:(^())]+[\)]|[\(]+[\w\d\s,]++[\)]|[\w]+\/[\d.\-\w]+|\w+#',
		'product' => '/^([A-Z]{1}[a-z]+)\/([\d.]+)$/',
		'os'	=> '/(?<=\().*(?=\))/',
	);
	
	
	protected $_checkLists = array(
		'product'	=>  array(
			'Mozilla',
			'Opera',
		),
		'os'		=> array(
	        'Windows NT 6.3'            =>  'Windows Blue',
	        'Windows NT 6.2'            =>  'Windows 8',
	        'Windows NT 6.1'            =>  'Windows 7',
	        'Windows NT 6.0'            =>  'Windows Vista',
	        'Windows NT 5.2'            =>  'Windows Server 2003/XP x64',
	        'Windows NT 5.1'            =>  'Windows XP',
	        'Windows xp'                =>  'Windows XP',
	        'Windows NT 5.0'            =>  'Windows 2000',
	        'Windows me'                =>  'Windows ME',
	        'win98'                     =>  'Windows 98',
	        'win95'                     =>  'Windows 95',
	        'win16'                     =>  'Windows 3.11',
	        'mac os x'                  =>  'Mac OS X',
	        'mac_powerpc'               =>  'Mac OS 9',
	        'ubuntu'                    =>  'Ubuntu',
	        'iphone'                    =>  'iPhone',
	        'ipod'                      =>  'iPod',
	        'ipad'                      =>  'iPad',
	        'android'                   =>  'Android',
	        'blackberry'                =>  'BlackBerry',
	        'webos'                     =>  'Mobile',
			'linux'                     =>  'Linux',			
		)
	);

	protected $_validateList = array(
		'product'	=>  array(
			'Mozilla/5.0'	=> true,
			'Mozilla/4.0'	=> true,
			'Opera/9.80'	=> false,
			'Opera/9.64'	=> false
		),
		'os'		=> array(
	        'Windows NT 6.3'            =>  'Windows Blue',
	        'Windows NT 6.2'            =>  'Windows 8',
	        'Windows NT 6.1'            =>  'Windows 7',
	        'Windows NT 6.0'            =>  'Windows Vista',
	        'Windows NT 5.2'            =>  'Windows Server 2003/XP x64',
	        'Windows NT 5.1'            =>  'Windows XP',
	        'Windows xp'                =>  'Windows XP',
	        'Windows NT 5.0'            =>  'Windows 2000',
	        'Windows me'                =>  'Windows ME',
	        'win98'                     =>  'Windows 98',
	        'win95'                     =>  'Windows 95',
	        'win16'                     =>  'Windows 3.11',
	        'mac os x'                  =>  'Mac OS X',
	        'mac_powerpc'               =>  'Mac OS 9',
	        'ubuntu'                    =>  'Ubuntu',
	        'iphone'                    =>  'iPhone',
	        'ipod'                      =>  'iPod',
	        'ipad'                      =>  'iPad',
	        'android'                   =>  'Android',
	        'blackberry'                =>  'BlackBerry',
	        'webos'                     =>  'Mobile',
			'linux'                     =>  'Linux',			
		)
	);

	
/**
 * RitaUserAgent::__construct()
 * 
 * @param mixed $userAgent
 * @return
 */
	public function __construct($checkCurrentUA = false, $validate = false) {
		if($checkCurrentUA) {
			return $this->detect($_SERVER['HTTP_USER_AGENT'], $validate);
		}
	}
	
/**
 * RitaUserAgent::detect()
 * 
 * @param mixed $userAgent
 * @return
 */
	public function detect($userAgent = null, $validate = false) {
		$this->report = $this->_report;
		
		if($userAgent === null) {
			$userAgent =  $_SERVER['HTTP_USER_AGENT'];
		}
		
		if (!is_string($userAgent)) {
			return false;	
		}
		$this->report['ua'] = $userAgent;
		preg_match_all($this->_patterns['parser'],$userAgent,$this->data);
		
		$this->data = (isset($this->data[0])) ? $this->data[0] : false;
		
		//$this->isValid == false;
		if ($this->data === false) {
			return false;
		}
		
		try {
			$this->checkProduct();
			$this->checkOs();
			
		} catch (Exception $e) {
			
		}
		
	
		return $this->report;
	}
	

	/**
	 * RitaBrowserDetect::checkOs()
	 * 
	 * @return void
	 */
	private function checkOs(){
		$data = $this->data[0];
		preg_match($this->_patterns['os'],$data,$matched);
		$matched = explode(';',$matched[0]);
		pr($matched);
		foreach($matched as $index => $value) {
			$value = trim($value);
			if(in_array($value,array('Macintosh','Windows','X11','Linux'))) {
				$this->report['platform'] = $value;	
				continue;
			}
			
			
		}
		//foreach($matched)
	//	foreach($matched as $key=>$val) {
//			$val = trim($val);
//			if(isset($this->_checkLists['os'][$val])){
//				
//				$this->report = $val;
//				continue;
//			};
//			pr($val. " aaaa");
//	  		if($val === 'WOW64'){
//				unset($matched[$key]);
//			}
//			
//			//$this->report[1][] = $key;
//	
//		}
		//$this->data[1] = $matched;
		//$this->isValid = false;

	}

	

/**
 * RitaUserAgent::checkProduct()
 * 
 * @return void
 */
	private function checkProduct(){
		$product =  explode('/',trim($this->data[0]));
		if (count($product) !== 2) {
			return false;	
		}
		list($this->report['product'], $this->report['productVersion']) =$product;
		unset($this->data[0]);
		$this->data = $this->reindex($this->data);
		return true;
	}


	private function reindex($data){
		return array_values($data);	
	}	
	
	/**
	 * RitaBrowserDetect::getBrowser()
	 * 
	 * @return
	 */
	public function getBrowser(){
		return $this->browser;
	}

	/**
	 * RitaBrowserDetect::getVersion()
	 * 
	 * @return
	 */
	public function getVersion(){
		return $this->browserVersion;
	}

	/**
	 * RitaBrowserDetect::getOs()
	 * 
	 * @return
	 */
	public function getOs(){
		return $this->os;
	}

	/**
	 * RitaBrowserDetect::isValid()
	 * 
	 * @return
	 */
	public function isValid(){
		return $this->isValid;
	}
	
}
