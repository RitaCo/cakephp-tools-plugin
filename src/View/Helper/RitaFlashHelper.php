<?php

namespace RitaTools\View\Helper;

use Cake\View\Helper;

class RitaFlashHelper extends \Cake\View\Helper\FlashHelper
{

    public function render($key = 'flash', array $options = [])
    {
        if (!$this->request->session()->check("Flash.$key")) {
            return;
        }

        $flashs = $this->request->session()->read("Flash.$key");
        if (!is_array($flashs)) {
            throw new \UnexpectedValueException(sprintf(
                'Value for flash setting key "%s" must be an array.',
                $key
            ));
        }
        $this->request->session()->delete("Flash.$key");
        
        $render = '';
        foreach ($flashs as $flash) {
            $flash = $options + $flash;
         
              $render = $render .$this->_View->element($flash['element'], $flash);
             
        }

        return $render;
    }
}
