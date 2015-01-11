<?php
namespace RitaTools\View\Helper;

use Cake\View\Helper\HtmlHelper as BaseHtmlHelper;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Network\Response;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

class RitaHtmlHelper extends BaseHtmlHelper
{


    public $helpers = ['Url'];
    
    /**
     * Breadcrumbs.
     *
     * @var array
     */
    public $_crumbs = array();

    protected $_ritaConfig  = [
    'templates' => [
    'tableheader' => '<th{{attrs}}><a href="#">{{content}}</a></th>',
    'tableheaderrow' => '<tr{{attrs}}>{{content}}</tr>',
    'tablecell' => '<td{{attrs}}>{{content}}</td>',
    'tablerow' => '<tr{{attrs}}>{{content}}</tr>',
    'linkDisabled' => '<a {{attrs}}>{{content}}</a>'


    ]
    ];


    public $_eventConfig = array(
    'activeLink' => 'active',
    'inlineActive' => 'inlineActive',
    );

    public function __construct(View $View, array $config = array())
    {
        $config = Hash::merge($this->_ritaConfig, $config);
        parent::__construct($View, $config);

    }
    


    /**
     * RitaHtmlHelper::_onActive()
     *
     *     `active` if exist active in option class 'active' add to link- paramete is [inline:equal]
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
    private function _onActive($url, $options)
    {
        $break = false;
        
        $onActive = $options['onActive'];
        unset($options['onActive']);
        
        if ($url === null) {
            $break = true;
        }
        
        $currentUrl = urldecode($this->request->here);
        $url =  $this->Url->build($url);
         
        if (!$break && ($onActive === true || $onActive === 'full')) {
            if ($currentUrl == $url) {
                $options = $this->addClass($options, $this->_eventConfig['activeLink']);
                $break = true;
            }
        }
        
        if (!$break && $onActive === 'inline') {
            $matches =preg_match("#^$url#", $currentUrl, $x);
            if ($matches === 1) {
                $options = $this->addClass($options, $this->_eventConfig['activeLink']);
                $options = $this->addClass($options, $this->_eventConfig['inlineActive']);
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
    private function _onHide($url, $options, $exit = false)
    {
        $onHide = $options['onHide'];
        unset($options['onHide']);
        
        if (is_callable($onHide)) {
            $onHide = call_user_func_array($onHide, array($url,$options));
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
    private function _onDisabled($url, $options)
    {
        $onDisabled = $options['onDisabled'];
        unset($options['onDisabled']);
        
        
        if ($onDisabled === true) {
            $url = false;
            $options = $this->addClass($options, 'disabled');
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
    public function link($title, $url = null, array $options = array())
    {
        $exit = false;
        
        if (!isset($options['onActive'])) {
            $options['onActive'] = true;
            
        }
            list($url,$options) = $this->_onActive($url, $options);

        if (isset($options['onHide'])) {
            list($url,$options,$exit)= $this->_onHide($url, $options);
        }

        if (isset($options['onDisabled'])) {
            list($url,$options) = $this->_onDisabled($url, $options);
            
        }
        
        if ($url === false) {
            $templater = $this->templater();
            return $templater->format('linkDisabled', [
                'attrs' => $templater->formatAttributes($options),
                'content' => $title
            ]);
        }
        return ($exit)? false : $this->_link($title, $url, $options);
    }
    

    /**
     * RitaHtmlHelper::linkIcon()
     *
     * @param mixed $title
     * @param mixed $url
     * @param mixed $options
     * @param bool $confirmMessage
     * @return
     */
    public function linkIcon($title, $url = null, $options = array(), $confirmMessage = false)
    {
        $title = sprintf('<i class="%s"></i>', $title);
        $options['escapeTitle'] = false;
        return $this->link($title, $url, $options, $confirmMessage);
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
    public function _link($title, $url = null, $options = array(), $confirmMessage = false)
    {
            
         return parent::link($title, $url, $options);
        $escapeTitle = true;
        if ($url === null) {
            $url = $this->Url->build($title, true);
            $title = htmlspecialchars_decode($url, ENT_QUOTES);
            $title = h(urldecode($title));
            $escapeTitle = false;
        } elseif ($url !== false) {
            $url = $this->Url->build($url, true);
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

        $confirmMessage = null;
        if (isset($options['confirm'])) {
            $confirmMessage = $options['confirm'];
            unset($options['confirm']);
        }
        if ($confirmMessage) {
            $options['onclick'] = $this->_confirm($confirmMessage, 'return true;', 'return false;', $options);
        }

        $templater = $this->templater();
        return $templater->format('link', [
        'url' => $url,
        'attrs' => $templater->formatAttributes($options),
        'content' => $title
        ]);
    }

    /**
     * RitaHtmlHelper::firstCrumb()
     *
     * @param mixed $name
     * @param mixed $link
     * @param mixed $options
     * @return
     */
    public function firstCrumb($name, $link = null, $options = null)
    {
        array_unshift($this->_crumbs, array($name, $link, $options));
        return $this;
    }
}
