<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace RitaTools\Database;

use Cake\Database\Driver;
use PDO;

/**
 * Encapsulates all conversion functions for values coming from database into PHP and
 * going from PHP into database.
 */
class Type extends \Cake\Database\Type{
s
/**
 * List of supported database types. A human readable
 * identifier is used as key and a complete namespaced class name as value
 * representing the class that will do actual type conversions.
 *
 * @var array
 */
	protected static $_types = [
		'biginteger' => 'Cake\Database\Type\IntegerType',
		'binary' => 'Cake\Database\Type\BinaryType',
		'date' => 'RitaTools\Database\Type\DateType',
		'float' => 'Cake\Database\Type\FloatType',
		'decimal' => 'Cake\Database\Type\FloatType',
		'integer' => 'Cake\Database\Type\IntegerType',
		'time' => 'Cake\Database\Type\TimeType',
		'datetime' => 'RitaTools\Database\Type\DateTimeType',
		'timestamp' => 'RitaTools\Database\Type\DateTimeType',
		'uuid' => 'Cake\Database\Type\UuidType',
	];

/**
 * List of basic type mappings, used to avoid having to instantiate a class
 * for doing conversion on these
 *
 * @var array
 */
	protected static $_basicTypes = [
		'string' => ['callback' => 'strval'],
		'text' => ['callback' => 'strval'],
		'boolean' => [
			'callback' => ['\Cake\Database\Type', 'boolval'],
			'pdo' => PDO::PARAM_BOOL
		],
	];
    
    
}
