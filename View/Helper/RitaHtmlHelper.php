<?php
//App::import('Core.Lib','pdate');
App::uses('HtmlHelper','View/Helper');
class RitaHtmlHelper extends HtmlHelper{
    
    public $_eventConfig = array(
		'activeLink' => 'active',
		'inlineActive' => 'inlineActive',
	);


	/**
	 * RitaHtmlHelper::__construct()
	 * 
	 * @param mixed $View
	 * @param mixed $settings
	 * @return void
	 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if (isset($settings['event'])){
			$this->_eventConfig = array($this->_eventConfig,$settings['event']);
		}
	}


	/**
	 * RitaHtmlHelper::_onActive()
	 * 
	 * 	`active` if exist active in option class 'active' add to link- paramete is [inline:equal]
	 * 
	 * ### Options
	 *
	 * - `true` equal with full
	 * - `full` Set to false to disable escaping of title. (Takes precedence over value of `escape`)
	 * - `confirm` JavaScript confirmation message.	 
	 * 
	 *  
	 * @param mixed $url
	 * @param string $mode
	 * @return void
	 */
	private function _onActive($url,$options ) {
		$break = false;	
		
		$onActive = $options['onActive'];
		unset($options['onActive']);
		
		if ($url === null) {
			$break = true;
		}
		
		$currentUrl = urldecode($this->request->here);
		$url =  $this->url($url);
		
		if (!$break && ($onActive === true || $onActive === 'full') ) {
			if ($currentUrl == $url) {
				$options = $this->addClass($options,$this->_eventConfig['activeLink']);
				$break = true;
			}
		}
		
		if (!$break && $onActive === 'inline'  ) {
			$matches =preg_match("#^$url#", $currentUrl,$x);
			if ($matches === 1) {
				$options = $this->addClass($options,$this->_eventConfig['activeLink']);
				$options = $this->addClass($options,$this->_eventConfig['inlineActive']);
			}
		}
		//if (is_array($onActive) && is_callable($onActive)){	}
		return array($url, $options);	
	}    


	/**
	 * RitaHtmlHelper::_onActive()
	 * 
	 * @param mixed $url
	 * @param mixed $options
	 * @param bool $exit
	 * @return
	 */
	private function _onHide($url,$options,$exit = false ){
		$onHide = $options['onHide'];
		unset($options['onHide']);
		
		if (is_callable($onHide)){
			$onHide = call_user_func_array($onHide,array($url,$options));	
		}
		if ($onHide === true) {
			$exit = true;
		}
		
		return array($url, $options, $exit);	
	}
	

	/**
	 * RitaHtmlHelper::_onHide()
	 * 
	 * @param mixed $url
	 * @param mixed $options
	 * @param bool $exit
	 * @return
	 */
	private function _onUnlink($url,$options ){
		$onUnlink = $options['onUnlink'];
		unset($options['onUnlink']);
		
		
		if ($onUnlink === true) {
			$url = '#';
		}
		
		return array($url, $options);	
	}
	
	
	/**
	 * RitaHtmlHelper::link()
	 * 
	 * @param mixed $title
	 * @param mixed $url
	 * @param mixed $options
	 * @param bool $confirmMessage
	 * @return
	 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$exit = false;
		if (isset($options['onActive'])){
			list($url,$options) = $this->_onActive($url,$options);
		}

		if (isset($options['onHide'])){
			list($url,$options,$exit)= $this->_onHide($url,$options);
		}

		if (isset($options['onUnlink'])){
			list($url,$options) = $this->_onActive($url,$options);
		}		
		return  ($exit)? false : parent::link($title,$url,$options,$confirmMessage);
	}    
	

    
    
    
    
}