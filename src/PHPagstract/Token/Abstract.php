<?php

namespace PHPagstract;

/**
 * parser token/tag object abstract
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class TokenAbstract {
	
	/**
	 * start of token
	 * @var string
	 */
	protected $startTag = "<";
	
	/**
	 * end of token
	 * @var string
	 */
	protected $endTag = "<";
	
	/**
	 * token namespace
	 * @var string
	 */
	protected $namespace = "pma";
	
	/**
	 * token name
	 * @var string
	 */
	protected $name = null;
	
	/**
	 * token attributes
	 * @var string
	 */
	protected $attributes = array();
	
	/**
	 * 
	 * @param string $token
	 * @param array $attributes (optional)
	 */
	public function __construct( $token, $attributes = array() ) {
		$this->setName($token);
		$this->setAttributes($attributes);
	}
	
	/**
	 * @return the $startTag
	 */
	public function getStartTag() {
		return $this->startTag;
	}

	/**
	 * @param string $startTag
	 */
	public function setStartTag($startTag) {
		$this->startTag = $startTag;
	}

	/**
	 * @return the $endTag
	 */
	public function getEndTag() {
		return $this->endTag;
	}

	/**
	 * @param string $endTag
	 */
	public function setEndTag($endTag) {
		$this->endTag = $endTag;
	}

	/**
	 * @return the $namespace
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
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
	 * @return the $attributes
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param string $attributes
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

	/**
	 * @return the $attributes[$key] 
	 */
	public function getAttribute( $key ) {
		return $this->attributes[$key];
	}
	
	
}

