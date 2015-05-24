<?php
namespace Rita\Tools\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\Utility\Hash;
use Cake\View\View;

class FormHelper extends CakeFormHelper
{

    public $helpers = ['Url','Rita.Html'];

    protected $_ritaConfig  = [
        'typeMap' => [
                'pdate' => 'date',
        ],
    'templates' => [
    'inputContainer' => '<div class="com-input {{type}}{{required}} {{axis}}"><div class="input-container">{{content}}</div></div>',
    'inputContainerError' => '<div class="com-input {{type}}{{required}} error {{axis}}"><div class="input-container">{{content}}{{error}}</div></div>',
    'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
    'submitContainer' => '<div class="submit">{{content}}</div>',
    'dateWidget' => '<div class="dateSelect">{{day}}{{month}}{{year}}{{localization}} <div class="divider"></div> {{hour}}{{minute}}{{second}}{{meridian}}</div>',
    ]
        ];
        



    /**
     * RitaFormHelper::__construct()
     *
     * @param mixed $View
     * @param mixed $config
     * @return void
     */
    public function __construct(View $View, array $config = [])
    {
        $config = Hash::merge($this->_ritaConfig, $config);
        //   $this->_defaultWidgets['pdate'] = ['\Rita\Tools\View\Widget\DateTimeWidget', 'select'];
        parent::__construct($View, $config);
        
    }
    
    /**
     * RitaFormHelper::create()
     *
     * @param mixed $model
     * @param mixed $options
     * @return
     */
    public function create($model = null, array $options = [])
    {
        $options['novalidate'] = true;
        //$options = Hash::mergeDiff($options,$_options);
        $options = $this->addClass($options, 'parentInherit');
        $options = $this->addClass($options, 'com-form');
        $options = $this->addClass($options, 'form-h');
        return parent::create($model, $options);
    }
    
    /**
     * RitaFormHelper::slug()
     *
     * @param mixed $fieldName
     * @param mixed $options
     * @return
     */
    public function slug($fieldName, $options = array())
    {
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
        if (strlen($url)!=1) {
            $url = $url.'/';
        }
//		
    //	$this->setEntity($fieldName);
        $script = $this->Html->script('libs/slug', array('inline' => false));
        $script .=    $this->Html->scriptBlock(
            "$('#".$bindField."').slug({
   target: $('#".$this->domId()."') 
});

$('#".$this->domId()."').slug({
	target : $('#".$this->domId()."'),
	event : 'focusout'
	
});"        ,
            array('inline' => true,'safe' =>false)
        );
        $options['between'] = '<div class="input-wrapper">';
            $options['after'] = '<span class="input-prepend">' . $url . '</span></div>'.$script; //. $options['after'];
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
    private function _onHide($url, $options, $exit = false)
    {
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
     * @return
     */
    public function postLink($title, $url = null, array $options = array())
    {
        $exit = false;
        if (isset($options['onHide'])) {
            list($url,$options,$exit)= $this->_onHide($url, $options);
        }
        return ($exit)? false : parent::postLink($title, $url, $options);
    }
    


    /**
     * RitaFormHelper::submit()
     *
     * @param mixed $caption
     * @param mixed $options
     * @return void
     */
    public function submit($caption = null, array $options = [])
    {
        
        $options = $this->addClass($options, 'btn btn-action');
        return parent::submit($caption, $options);
    }
    

}
