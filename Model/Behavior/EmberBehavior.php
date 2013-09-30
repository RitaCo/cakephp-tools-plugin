<?php
/**
 * EmberBehavior
 * 	Serializer output query's of Model to json-api.com for Ember-data
 * 
 * @package RitaTools.Behavior
 * @author Mohammad Saleh Souzanchi
 * @copyright 2013  MIT
 * @link https://github.com/RitaCo/RitaTools
 * 
 * @version 0.0.1
 */
 
App::uses('EmberSerializers','RitaTools.Lib');
 
class EmberBehavior extends ModelBehavior {


	private $_configs = array(
		'auto'		=> false,
		'embedded'	=> false
	);


/**
 * EmberBehavior::setup()
 * 
 * @param mixed $model
 * @param mixed $settings
 * @return void
 */
	public function setup(Model $model, $settings = array()) {
		$this->settings[$model->alias]['Ember'] = array_merge($this->_configs,$settings);
	}

/**
 * EmberBehavior::beforeFind()
 * 
 * @param mixed $model
 * @param mixed $query
 * @return
 */
	public function beforeFind(Model $model, $query) {
		if (isset($query['toEmber']) && $query['toEmber'] ) {
			if(is_array($query['toEmber'])) {
				$this->settings[$model->alias]['Ember'] = $query['toEmber'];
			} else {
				$this->settings[$model->alias]['Ember']['auto'] = true ;
			}
		}
		return $query;
	}	

/**
 * EmberBehavior::afterFind()
 * 
 * @param mixed $model
 * @param mixed $results
 * @param mixed $primary
 * @return
 */
	public function afterFind(Model $model, $results, $primary = false) {
		if( !empty($results) && $primary && $this->settings[$model->alias]['Ember']['auto'] ){
			$results = $model->toEmber($results,$this->settings[$model->alias]['Ember']['embedded']);
		}
		return $results;
	}	
	

	public function toEmber(Model $model,$data,$embedded = false){
		$map = array(
			'model'	=> $model->alias,
			'relation' => $model->getAssociated()
		);
		$EmberSerializers = new EmberSerializers($data,$embedded,$map);
		return $EmberSerializers->convert();
	}


}
