<?php

namespace PHPagstract\Symbol\Symbols;

use PHPagstract\Token\Tokens\Token;

/**
 * symbol interface class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
interface Symbol
{

	/**
	 * convert symbol to string representation
	 *
	 * @return string
	 */
	public function toString();
    
	/**
	 * convert symbol data to array
	 *
	 * @return array
	 */
	public function toArray();

	/**
	 * get the token
	 *
	 * @return Token
	 */
	public function getToken();
    
}
