<?php

namespace PHPagstract\Token;

/**
 * parser token/tag-attribute object abstract 
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class TokenAttributeAbstract {
	
	/**
	 * attribute name
	 * @var string
	 */
	protected $name = null;
	
	/**
	 * attribute value
	 * @var string
	 */
	protected $value = "";
	
	/**
	 * attribute default value
	 * @var string
	 */
	protected $defaultvalue = "";
	
	/**
	 * attribute type
	 * @var string
	 */
	protected $type = "";
	
	/**
	 * 
	 * @param string $name
	 * @param string $defaultvalue
	 */
	public function __construct( $name, $defaultvalue = "", $type = "value" ) {
		$this->setName($name);
		$this->setValue($defaultvalue);
		$this->setType($type);
	}
	
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return the $value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * @return the $defaultvalue
	 */
	public function getDefaultValue() {
		return $this->defaultvalue;
	}

	/**
	 * @param string $defaultvalue
	 */
	public function setDefaultValue($defaultvalue) {
		$this->defaultvalue = $defaultvalue;
	}
	
	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}


	
	
}

