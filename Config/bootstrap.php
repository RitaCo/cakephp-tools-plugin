<?php
/**
 * define path of Rita Tools
 */
define('RITATOOLS_DIR', (dirname(dirname(__FILE__))) . DS );


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

//include  CakePlugin::path('RitaTools').'Vendor'.DS.'pua'.DS.'lib'.DS.'phpUserAgent.php';
//include  CakePlugin::path('RitaTools').'Vendor'.DS.'pua'.DS.'lib'.DS.'phpUserAgentStringParser.php';
//include  CakePlugin::path('RitaTools').DS.'Vendor'.DS.'RitaUserAgent'.DS.'RitaUserAgent.php';





