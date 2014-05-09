<?php
/**
 * SluggableBehavior
 * 
 * @package Plugin.Core.Behavior  
 * @author Rita Web Developer by Saleh Souzanchi
 * @version 2.1.1
 * @access public
 */
class SluggableBehavior extends ModelBehavior {

	public $__settings = array( 
		'label' => 'name' ,
		'slug'  => 'slug'
	);

/**
 * settings array(
 *   'label' : field name for convert to slug 
 *   'slug'  : field name to save slug string
 * )
 * 
 **/
	public function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = array('label' => 'name', 'slug' => 'slug');
		}

		$this->settings[$model->alias] = array_merge(
			$this->settings[$model->alias], (array)$settings
		);

		if (!$model->hasField($this->settings[$model->alias]['label'])) {
			$this->settings[$model->alias]['label'] = $model->displayField;
		}
	}

/**
 * SluggableBehavior::beforeValidate()
 * 
 * @param mixed $Model
 * @return
 */
	public function beforeValidate(Model $Model, $options = array()) {
		$settings = $this->settings[$Model->alias];
		if ( $Model->hasField($settings['slug']) and  isset( $Model->data[$Model->alias][$settings['label']] ) ){
			//if ( ! $Model->hasField($this->__settings['label']) ) return true;
			if ( empty($Model->data[$Model->alias][$settings['slug']])  ) {
				$Model->data[$Model->alias][$settings['slug']] = $this->persian_Slug_Format($Model->data[$Model->alias][$settings['label']]);
			} else {
				$Model->data[$Model->alias][$settings['slug']] = $this->persian_Slug_Format($Model->data[$Model->alias][$settings['slug']]);
			}
		}
		return true;  
	}

	    
/**
 * SluggableBehavior::persian_Slug_Format()
 * 
 * @param mixed $filename
 * @return
 */
	public function persian_Slug_Format($filename) {
		$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'","\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
		$filename = str_replace($special_chars, '', $filename);
		$filename = preg_replace('/[\s-]+/', '-', $filename);
		$filename = trim($filename, '.-_');
		return $filename;
	}


}