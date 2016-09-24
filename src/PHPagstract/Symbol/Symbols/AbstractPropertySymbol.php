<?php

namespace PHPagstract\Symbol\Symbols;

/**
 * abstract property symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractPropertySymbol {
    
	/**
	 * symbol name
	 *
	 * @var string
	 */
	private $name = 'Symbol';
    
	/**
	 * property type
	 *
	 * @var string
	 */
	private $type = null;

	/**
	 * parent symbol
	 * 
	 * @var Symbol
	 */
	private $parent;

	/**
	 * property container/reference
	 *
	 * @var \stdClass
	 */
	private $property = null;

	/**
	 * @param string $type
	 * @param string $name
	 * @param null|AbstractPropertySymbol $parent
	 */
	public function __construct($type, $name, AbstractPropertySymbol & $parent = null) {
		if ($parent !== null) {
			$this->parent = & $parent;
		}
		$this->setName($name);
		$this->setType($type);
	}
    
	/**
	 * convert symbol data to array
	 *
	 * @return array
	 */
	public function serialize()
	{
		$result = get_object_vars($this);
		if (isset($result["parent"])) {
			unset($result["parent"]);
		}
		if (isset($result["items"])) {
			$items = [];
			foreach ($result["items"] as $key => $item) {
				$items[] = $item->serialize();
			}
			$result["items"] = $items;
		}
		if (isset($result["properties"])) {
			$properties = get_object_vars($result["properties"]);
			$items = [];
			foreach ($properties as $key => $item) {
				$items[$key] = $item->serialize();
			}
			$result["properties"] = (object) $items;
		}
		return $result;
	}
    
	//
	// get/set new property
	//
    
	/**
	 * get a property's value
	 * 
	 * @param string $property the property name
	 * @return mixed|NULL
	 */
	public function get($property) {
		if (isset($this->$property)) {
			return $this->$property;
		}
		return null;
	}
    
	/**
	 * set a property's value
	 * 
	 * @param string $property the property name
	 * @param mixed $value
	 * @return self
	 */
	public function set($property, $value) {
		$this->$property = $value;
		return $this;
	}
    
	//
	// getter/setters
	//

	/**
	 * get the property
	 *
	 * @return Symbol
	 */
	public function getParent() 
	{
		if ($this->parent === null) {
			return $this;
		}
		return $this->parent;
	}
    
	/**
	 * set the property
	 *
	 */
	public function setParent(AbstractPropertySymbol $parent) 
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * get the property
	 *
	 * @return \stdClass
	 */
	public function getProperty() 
	{
		return $this->property;
	}
    
	/**
	 * set the property
	 *
	 * @param mixed $property
	 */
	public function setProperty($property) 
	{
		$this->property = $property;
		return $this;
	}

	/**
	 * get the property type
	 *
	 * @return string
	 */
	public function getType() 
	{
		return $this->type;
	}
    
	/**
	 * set the property type
	 *
	 * @param string $type
	 */
	public function setType($type) 
	{
		$this->type = $type;
		return $this;
	}
    
	/**
	 * get the name
	 *
	 * @return string
	 */
	public function getName() 
	{
		return (string) $this->name;
	}
    
	/**
	 * set the name
	 *
	 * @param string $name
	 */
	public function setName($name) 
	{
		$this->name = $name;
		return $this;
	}

}

