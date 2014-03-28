<?php
//App::import('Core.Lib','pdate');
App::uses('FormHelper','View/Helper');
class RitaFormHelper extends FormHelper{
    
    
    public function create($model = null, $options = array()){
    	$options['novalidate'] = true;
    	//$options = Hash::mergeDiff($options,$_options);
		$options = $this->addClass($options, 'parentInherit');
		$options = $this->addClass($options, 'com-form');
		$options = $this->addClass($options, 'form-h');
    	return parent::create($model, $options );
    }
    
    public function slug($fieldName, $options = array() ){
        $defaults = array(
            'format' => array(
                'before',
                'label',
                'between',
                'input',
                'after',
                'error',
            ),
            'div' => array(
                'class' => 'input text addon-slug'
            ),
            'empty' => '/',
            'url' => '/',
        );
		$options = Hash::merge($defaults, $options);
	    $options = parent::_initInputField($fieldName, $options);
		$bindField = $this->_getModel($this->model())->actsAs['Rita.Sluggable']['label'];
		$bindField = $this->model().'.'.$bindField;
		$this->setEntity($bindField);
		$bindField = $this->domId();
		$this->setEntity($fieldName);
		
		$val =  $this->value();
		$val = $val['value'];
		$url = $options['url'];
//		$url = array_filter( explode('/',$url) );
//		$url = array_flip($url);
//		unset($url[$val]);
//		$url = array_flip($url);
//		$url = '/'.implode('/',$url).'/';
		$url = Router::normalize($url);				
		if(strlen($url)!=1){
			$url = $url.'/';
		}
//		
	//	$this->setEntity($fieldName);
	$script = $this->Html->script('libs/slug',array('inline' => false));	
        $script .=	$this->Html->scriptBlock(
			"$('#".$bindField."').slug({
   target: $('#".$this->domId()."') 
});

$('#".$this->domId()."').slug({
	target : $('#".$this->domId()."'),
	event : 'focusout'
	
});"
		,array('inline' => true,'safe' =>false)	
			);
			$options['between'] = '<div class="input-wrapper">';
            $options['after'] = '<span class="input-prepend">' . $url . '</span></div>'.$script ; //. $options['after'];
        unset( $options['options'],$options['empty'],$options['link']);

		
        return parent::input($fieldName, $options);    		
        
    }
    


	/**
	 * RitaFormHelper::_onHide()
	 * 
	 * @param mixed $url
	 * @param mixed $options
	 * @param bool $exit
	 * @return
	 */
	private function _onHide($url,$options,$exit = false ){
		$onHide = $options['onHide'];
		unset($options['onHide']);
		
		
		if ($onHide === true) {
			$exit = true;
		}
		
		return array($url, $options, $exit);	
	}
	    
    
    
	/**
	 * RitaFormHelper::postLink()
	 * 
	 * @param mixed $title
	 * @param mixed $url
	 * @param mixed $options
	 * @param bool $confirmMessage
	 * @return void
	 */
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		$exit = false;
		if (isset($options['onHide'])){
			list($url,$options,$exit)= $this->_onHide($url,$options);
		}
		return  ($exit)? false : parent::postLink($title,$url,$options,$confirmMessage);		
	}    
    


/**
 * RitaFormHelper::submit()
 * 
 * @param mixed $caption
 * @param mixed $options
 * @return void
 */
	public function submit($caption = null, $options = array()) {
		
		$options = $this->addClass($options,'btn btn-action');
		return parent::submit($caption, $options);
	}    
    

/**
 * RitaFormHelper::input()
 * 
 * @param mixed $fieldName
 * @param mixed $options
 * @return void
 */
	public function input($fieldName, $options = array()) {
		$rita = true;
		if (isset($options['rita'])) {
			$rita = $options['rita'];
			unset($options['rita']);
		}
		
		if ($rita) {
			$before = '<div class="input-container">';
			$after = "</div>";
			
			if (isset($options['before'])) {
				$before = $options['before'].$before;
			}
			if (isset($options['after'])) {
				$after = $after.$options['after'];
			}
			$options['before'] = $before;	
			$options['after'] = $after;	
		}
		
		
		return parent::input($fieldName, $options );
	}



/**
 * RitaFormHelper::_divOptions()
 * 
 * @param mixed $options
 * @return
 */
	protected function _divOptions($options) {
		if ($options['type'] === 'hidden') {
			return array();
		}
		$div = $this->_extractOption('div', $options, true);
		if (!$div) {
			return array();
		}
	
		$divOptions = array('class' => 'com-input');
		$divOptions = $this->addClass($divOptions, $options['type']);
		if (is_string($div)) {
			$divOptions['class'] = $div;
		} elseif (is_array($div)) {
			$divOptions = array_merge($divOptions, $div);
		}
		if (
			$this->_extractOption('required', $options) !== false &&
			$this->_introspectModel($this->model(), 'validates', $this->field())
		) {
			$divOptions = $this->addClass($divOptions, 'required');
		}
		if (!isset($divOptions['tag'])) {
			$divOptions['tag'] = 'div';
		}
		return $divOptions;
	}
	        
}