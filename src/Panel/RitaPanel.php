<?php
namespace Rita\Tools\Panel;
use DebugKit\DebugPanel;
/**
 * Variables Panel
 *
 * Provides debug information on the View variables.
 *
 * @package       cake.debug_kit.panels
 */
class RitaPanel extends DebugPanel {

//	public $plugin = 'Rita';
//	public $elementName = 'DebugKit/rita_panel';
/**
 * beforeRender callback
 *
 * @return array
 */
	public function beforeRender(Controller $controller) {
		$out = array();
		$x = Configure::read();
		$out['Rita'] = $x['Rita'];
		unset($x['Rita']);
		$out['Configure'] = $x;
		$out['Request']= get_class_vars(get_class($controller->request));
		unset($x);
		return $out;
	}

}
