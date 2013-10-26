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
		
		$this->loadConfig('tags.php',App::pluginPath('RitaTools').'Config'.DS);
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
	private function _onDisabled($url,$options ){
		$onDisabled = $options['onDisabled'];
		unset($options['onDisabled']);
		
		
		if ($onDisabled === true) {
			$url = false;
			$options = $this->addClass($options,'disabled');
			$exit = true;
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

		if (isset($options['onDisabled'])){
			list($url,$options) = $this->_onDisabled($url,$options);
		}		
		return  ($exit)? false : $this->_link($title,$url,$options,$confirmMessage);
	}    
	
	
		/**
		 * RitaHtmlHelper::_link()
		 * 
		 * @param mixed $title
		 * @param mixed $url
		 * @param mixed $options
		 * @param bool $confirmMessage
		 * @return
		 */
		public function _link($title, $url = null, $options = array(), $confirmMessage = false) {
			
		$escapeTitle = true;
		if($url === null) {
			$url = $this->url($title);
			$title = htmlspecialchars_decode($url, ENT_QUOTES);
			$title = h(urldecode($title));
			$escapeTitle = false;
		} elseif($url !== false){
			$url = $this->url($url);	
		}
		
	

		if (isset($options['escapeTitle'])) {
			$escapeTitle = $options['escapeTitle'];
			unset($options['escapeTitle']);
		} elseif (isset($options['escape'])) {
			$escapeTitle = $options['escape'];
		}

		if ($escapeTitle === true) {
			$title = h($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}
		if ($confirmMessage) {
			$options['onclick'] = $this->_confirm($confirmMessage, 'return true;', 'return false;', $options);
		} elseif (isset($options['default']) && !$options['default']) {
			if (isset($options['onclick'])) {
				$options['onclick'] .= ' ';
			} else {
				$options['onclick'] = '';
			}
			$options['onclick'] .= 'event.returnValue = false; return false;';
			unset($options['default']);
		}

		if ($url === false) {
			return sprintf($this->_tags['linknohref'], $this->_parseAttributes($options), $title);
		}		
		return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
	}

    
    
    
    
}