<?php
/**
 * define path of Rita Tools
 */
 
if (!defined('RITATOOLS_DIR')) {
	define('RITATOOLS_DIR', (dirname(dirname(__FILE__))) . DS );
}

include RITATOOLS_DIR . 'Lib' . DS . 'Functions.php';
App::uses('RitaTools','RitaTools.Lib');





RitaTools::libRepo('jquery',function($cdn = false ,$version = null){
		if ($cdn) {
			return 'aaa';
		}
		
		if (!$cdn) {
			return '//code.jquery.com/jquury.js';
		}
		
		return false;	
});


RitaEvent::on('Model.beforeInit',function(CakeEvent $event){
	$event->subject()->actsAs[]= 'RitaTools.RitaValidates';
   		
	//	$event->subject()->components[] = 'DataGridder.DataGrid';
	//$event->subject()->helpers[] = 'DataGridder.DataGrid';
		 
     
});

//include  CakePlugin::path('RitaTools').'Vendor'.DS.'pua'.DS.'lib'.DS.'phpUserAgent.php';
//include  CakePlugin::path('RitaTools').'Vendor'.DS.'pua'.DS.'lib'.DS.'phpUserAgentStringParser.php';
//include  CakePlugin::path('RitaTools').DS.'Vendor'.DS.'RitaUserAgent'.DS.'RitaUserAgent.php';





