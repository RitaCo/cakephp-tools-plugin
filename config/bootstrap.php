<?php
require ('functions.php');
use Cake\Core\Configure;

    $paths = Configure::read('App.paths.templates');
    $paths[] = dirname(__DIR__) . DS . 'src' . DS . 'Template' . DS;
    Configure::write('App.paths.templates', $paths);
    unset($paths);
