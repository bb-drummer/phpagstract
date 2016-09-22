<?php
namespace PHPagstract\Symbol;

use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;
use PHPagstract\Symbol\Symbols\Properties\RootProperty;
use PHPagstract\Symbol\Symbols\Properties\ScalarProperty;
use PHPagstract\Symbol\Symbols\Properties\ListProperty;
use PHPagstract\Symbol\Symbols\Properties\ObjectProperty;

/**
 * PHPagstract property reference resolver class
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyReferenceResolver {
    
	/**
	 * regular expression to parse reference string representation
	 * ex: ".artikel.verknuepfungen.kacheln[0].liste[1].label"
	 * 
	 * @var string|RegEx
	 */
	private $parseRegex = "/(\\.\\.\\/\\.|\\.)?([a-zA-Z0-9\\-\\_]*)\\[?([0-9]*)]?/i";
    
	/**
	 * regular expression to match invalid characters in a reference string representation
	 * aka, every character which is not allowed in a string like: ".artikel.verknuepfungen.kacheln[0].liste[1].label"
	 * 
	 * @var string|RegEx
	 */
	private $errorRegex = "/([^a-zA-Z0-9\-\_\]\[\.\/])/i";
    
	/**
	 * context, a.k.a. data container to map the reference to
	 * 
	 * @var array|object
	 */
	private $context = [
		"root" => []
	];
    
	/**
	 */
	public function __construct() 
	{
	}
    
	/**
	 * symbolize (json) data into special (Pagstract) data types
	 * 
	 * @param mixed $data
	 * @return mixed
	 */
	public function symbolize ( $data, AbstractPropertySymbol $parent = null, $name = null ) 
	{
    	
		if ( ($parent === null) ) {
			// no parent, so create a root node
			$root = new RootProperty();
			if (isset($data->root)) {
			  	$data = $data->root;
			}
			$rootProperties = get_object_vars($data);
			foreach ($rootProperties as $name => $value) {
				$properties[$name] = $this->symbolize($value, $root, $name);
			}
		   	$root->set('properties', (object)$properties);
			return ($root);
		}
    	
		switch (true) {
    		
			case $this->isNull($data):
				// set null property type
				$property = new ScalarProperty($name, $parent);
				$property->setProperty($data);
			break;
    		
			case $this->isList($data): 
				// set list property type
				// try to detect a component?
				$property = new ListProperty($name, $parent);
				$items = [];
				foreach ($data as $idx => $item) {
					$items[] = $this->symbolize($item, $property, $idx);
				}
				$property->set('items', $items);
			break;
    		
			case $this->isObject($data): 
				// set object property type
				// try to detect a component?
				$property = new ObjectProperty($name, $parent);
				$objProperties = get_object_vars($data);
				$properties = [];
				foreach ($objProperties as $xname => $value) {
					$properties[$xname] = $this->symbolize($value, $property, $xname);
				}
				$property->set('properties', (object)$properties);
			break;

			case $this->isScalar($data):
			default: 
				// set scalar property type
				// try to detect component
				$property = new ScalarProperty($name, $parent);
				$property->setProperty($data);
			break;
    		
		}
    	
		return $property;
	}
    
	/**
	 * parse a reference string into parts
	 * 
	 * @param string $reference
	 * @return array
	 */
	public function parsePropertyReferenceString ( $reference ) 
	{
        
		$tokens = array();
		preg_match_all($this->parseRegex, $reference, $tokens);
		$errors = preg_match_all($this->errorRegex, $reference);
		if ( ($errors !== false) && ($errors > 0) ) {
			return array();
		}
		$parts = array();
		foreach ($tokens[2] as $idx => $property) {
			if (!empty($property)) {
				$parts[] = array(
					$tokens[1][$idx], // dot/parent
					$tokens[2][$idx], // property-name
					$tokens[3][$idx], // list index
				);
			}
		}
		return $parts;
	}
    
	/**
	 * resolve a reference string to data object/array
	 * 
	 * @param string $reference
	 * @return mixed
	 */
	public function getPropertyByReference ( $reference ) 
	{
        
        
		if ( strpos($reference, "../") === 0) {
			// we have a parental reference here, so get parent
			$data = $this->getContext()->getParent();
			$parentsName = $data->getName();
			if ( ($parentsName === "0") || is_numeric( $parentsName ) ) {
				// we are in a list and actual parent is the numeric index item, so get parent's parent
				// check if this one here is really needed anymore
			}
			$reference = mb_substr($reference, 3);
			$this->setContext($data);
			$this->getPropertyByReference($reference);
		}

		$data = $this->getContext();

		$tokens = $this->parsePropertyReferenceString($reference);
		foreach ($tokens as $key => $value) {
			$propertyName = $value[1];
			$index = $value[2];

			$type = $data->getType();
			if ( in_array($type, array('root', 'object')) ) {
				$properties = $data->get('properties');
				if ( isset($properties->$propertyName) ) {
		   			$data = $properties->$propertyName;
		   		}
		   		$items = $data->get('items');
		   		if ( ($index != '') && isset($items[$index]) ) {
		   			$data = $items[$index];
		   		}
			}
		}
        
		$rootContext = $this->getContext();
		if ( ($rootContext->getName() == $data->getName()) && ($rootContext->getParent()->getName() == $data->getParent()->getName()) ) {
			return null;
		}
        
		return $data;
	}
    
	/**
	 * resolve a reference string to data object/array
	 *
	 * @param string $reference
	 * @return mixed
	 */
	public function getValueByReference ( $reference ) 
	{
		$property = $this->getPropertyByReference($reference);
		switch ($property->getType()) {
			case 'object' : return $property->get('properties');
			case 'list' : return $property->get('items');
			default : return $property->getProperty();
		}
	}
    
	/**
	 * get the $context data container
	 * 
	 * @return array|object
	 */
	public function getContext() 
	{
		return $this->context;
	}

	/**
	 * set the $context data container
	 * 
	 * @param array|object $context
	 */
	public function setContext( &$context ) 
	{
		$this->context = $context;
	}
    
	/**
	 * check for value type 'object'
	 * 
	 * @param mixed $param
	 * @return boolean
	 */
	public function isObject($param) 
	{
		return ($param instanceof \stdClass );
	}


	/**
	 * check for value type 'scalar' (string, int, float, bool)
	 *
	 * @param mixed $param
	 * @return boolean
	 */
	public function isScalar($param) 
	{
		return (is_int($param) || is_float($param) || is_string($param) || is_bool($param));
	}


	/**
	 * check for value type 'null'
	 *
	 * @param mixed $param
	 * @return boolean
	 */
	public function isNull($param) 
	{
		return ($param === null);
	}


	/**
	 * check for value type 'list'
	 *
	 * @param mixed $param
	 * @return boolean
	 */
	public function isList($param) 
	{
		return (is_array($param));
	}
    
}
