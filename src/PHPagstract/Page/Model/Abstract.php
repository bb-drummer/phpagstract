<?php

namespace PHPagstract;

/**
 * page-model object abstract
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class PageModelAbstract {
	
	/**
	 * pagemodel name
	 * @var string
	 */
	protected $name = null;
	
	/**
	 * templates sources path
	 * @var string
	 */
	protected $sourcespath = null;
	
	/**
	 * replace path for "resource:" urls/links
	 * @var string
	 */
	protected $resources = "./";
	
	/**
	 * replace path for "resource_ext:" urls/links
	 * @var string
	 */
	protected $resources_ext = "./";
	
	/**
	 * pagemodel data
	 * @var mixed
	 */
	protected $data = null;
	
	/**
	 * current 'mandantId'
	 * @var int
	 */
	protected $mandantId = 2;
	
	/**
	 * 
	 * @param string $name
	 * @param string $sourcespath
	 * @param mixed $data
	 * @param string $resources
	 * @param string $resources_ext
	 */
	public function __construct( $name, $sourcespath, $data = array(), $resources = "./", $resources_ext = "./") {
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
	 * @return the $sourcespath
	 */
	public function getSourcespath() {
		return $this->sourcespath;
	}

	/**
	 * @param string $sourcespath
	 */
	public function setSourcespath($sourcespath) {
		$this->sourcespath = $sourcespath;
	}

	/**
	 * @return the $resources
	 */
	public function getResources() {
		return $this->resources;
	}

	/**
	 * @param string $resources
	 */
	public function setResources($resources) {
		$this->resources = $resources;
	}

	/**
	 * @return the $resources_ext
	 */
	public function getResources_ext() {
		return $this->resources_ext;
	}

	/**
	 * @param string $resources_ext
	 */
	public function setResources_ext($resources_ext) {
		$this->resources_ext = $resources_ext;
	}

	/**
	 * @return the $data
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @return the $mandantId
	 */
	public function getMandantId() {
		return $this->mandantId;
	}

	/**
	 * @param number $mandantId
	 */
	public function setMandantId($mandantId) {
		$this->mandantId = $mandantId;
	}

	
	
}

