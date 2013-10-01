<?php
/**
 * EmberSerializers
 * 	Serializer Array To json 
 * 
 * @package RitaTools.Lib
 * @author Mohammad Saleh Souzanchi
 * @copyright 2013  MIT
 * @link https://github.com/RitaCo/RitaTools
 * 
 * @version 0.0.1
 */
class EmberSerializers {

	private $data = array();
	private $embedded = false; 
	private $map = array();
	private $models = array();
	private $configDef = array(
				'embed' => false,
				'fields' => false,
				'include' => array(),
			);
	private $includeDef = array(
				'fields' => false, 
				'embed' => false,
				'key' => false,
				'include' => false,
			);	
	
	
/**
 * EmberSerializers::__construct()
 * 
 * @param mixed $data
 * @param bool $embedded
 * @param mixed $map
 * @return void
 */
	public function __construct($data = array(), $embedded = false, $map = array()) {
		$this->data = $data;
		$this->embedded = $embedded;
		$this->map = $map;
	}


/**
 * EmberSerializers::convert()
 * 
 * @return
 */
	public function convert() {
		if ( !is_array($this->data) or empty($this->data)) {
			return false;
		}
		if ( !is_array($this->map) or empty($this->map)){
			return false;
		}

		//if($this->embedded){
//			$this->extractRelation($this->data);
//		}
		 $this->scan($this->data,0);
		return $this->models;
	}

/**
 * EmberSerializers::scan()
 * 
 * @param mixed $data
 * @param mixed $level
 * @return
 */
	private function scan($data,$level) {
		if (!isset($data[0])){
			$data = array($data);
		}
		foreach($data as $key => $record){
			 $this->extract($record,$level);	
		}
	}


/**
 * EmberSerializers::extract()
 * 
 * @param mixed $record
 * @param mixed $level
 * @return
 */
	private function extract($record,$level) {
		extract($this->map);
		
		$out = $record[$model];
		
		foreach($relation as $modelName => $type ) {
			$key =  (in_array($type, array('hasOne','belongsTo')))? '_id' : '_ids';
			$key = strtolower($modelName).$key;
			$association = $record[$modelName];
			
			if (isset($association[0])){
				$out[$key] =  Hash::extract($association,'{n}.id');
			} else {
				if($association['id'] === null){
					continue;
				}
				$out[$key] = $association['id'];
			}
			
			$this->associationsMerege($modelName,$association);
			
		}

		$this->models[ Inflector::tableize($model)][] = $out;
	}
	
/**
 * EmberSerializers::associationsMerege()
 * 
 * @param mixed $name
 * @param mixed $association
 * @return void
 */
	private function associationsMerege($name,$association) {
		$name= Inflector::tableize($name);
		if (!isset($this->models[$name])){
			$this->models[$name] = array();
		}
		if(!isset($association[0])) {
			$association = array($association);
		}
		
		
		foreach($association as $key => $rec) {
			
			if($rec['id'] !== null && !Hash::contains($this->models[$name], $rec) ) {
				$this->models[$name][] =  $rec;
			}
		}
	}


	
	
	

}