<?php

namespace PHPagstract\Token;

/**
 * parser token/tag-attribute object class 
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class TokenAttribute extends TokenAttributeAbstract {
	
	/**
	 *
	 * @param string $name        	
	 *
	 * @param string $defaultvalue        	
	 *
	 */
	public function __construct($name, $defaultvalue = "", $type = "value") {
		parent::__construct ( $name, $defaultvalue = "", $type = "value" );
	}
}

