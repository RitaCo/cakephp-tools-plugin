<?php
namespace Rita\Tools\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;

class FlashComponent extends \Cake\Controller\Component\FlashComponent
{

    
    /**
     * FlashComponent::set()
     * 
     * @param mixed $message
     * @param mixed $options
     * @return void
     */
    public function set($message, array $options = [])
    {
        $options += $this->config();
    
        if ($message instanceof \Exception) {
            $options['params'] += ['code' => $message->getCode()];
            $message = $message->getMessage();
        }
    
        list($plugin, $element) = pluginSplit($options['element']);
    
        if ($plugin) {
            $options['element'] = $plugin . '.Flash/' . $element;
        } else {
            $options['element'] = 'Flash/' . $element;
        }
    
        $session = $this->_session->read('Flash.' . $options['key']);
        $session[] = [
            'message' => $message,
            'key' => $options['key'],
            'element' => $options['element'],
            'params' => $options['params']
        ];
        $this->_session->write('Flash.' . $options['key'], $session);
    }
}
