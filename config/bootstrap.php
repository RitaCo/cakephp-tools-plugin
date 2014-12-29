<?php
use Cake\Core\Configure;
	include('functions.php');
    
    $paths = Configure::read('App.paths.templates');
    $paths[] = dirname(__DIR__) . DS . 'src' . DS . 'Template' . DS; 
    Configure::write('App.paths.templates',$paths);
    unset($paths);
    