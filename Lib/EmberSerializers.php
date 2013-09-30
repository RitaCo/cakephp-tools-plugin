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
			$key =  (in_array($type, array('belongsTo')))? '_id' : '_ids';
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

	/**
	 * Serializers::normal()
	 * 
	 * @param mixed $data
	 * @param mixed $level
	 * @return
	 */
	private function relations( $data,$config){
		$keys = (!empty($config))? array_keys($config) : array_keys($data);
		$out =array();
		foreach( $keys as $key){
		  if (!is_array($data[$key]))  continue;
		  
		  $alias = (isset($data[$key][0]) )?  Inflector::tableize($key) : strtolower($key) ;
		  $out[$alias] = $data[$key];  	
		}
		
		return $out ;
	}
	
	
	
	
	/**
	 * Serializers::checkConfig()
	 * 
	 * @param mixed $config
	 * @return void
	 */
	private function checkConfig($config){
		$config = Hash::merge($this->configDef,$config);
		$this->include = $config['include'];
		unset($config['include']);
    	$this->config= $config;
	}


	
	/**
	 * Serializers::checkInclude()
	 * 
	 * @param mixed $include
	 * @param bool $deep
	 * @return
	 */
	private function checkInclude($include,$deep = false){
		if (is_string($include)){
			$include = array($include);
		}
		$include = Hash::normalize($include);

		foreach($include as $key => $val){
			$val = Hash::merge($this->includeDef,$val);
			// if Embed in config is true all include array set true
			if($this->config['embed']){
				$val['embed'] = true;
			}
			if ($val['include']){
				$val['include'] = $this->checkInclude($val['include'],true);
			}
			$include[$key] = $val;

		}
		
		
		if (!$deep){
			$this->include = $include;
		}
    	return $include;
	}


	
	public	function err($str){
		return array('Error',$str);
	}
}