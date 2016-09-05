<?php

namespace PHPagstract\Symbol\Symbols;

use PHPagstract\Token\Tokens\Token;

/**
 * symbol abstract class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractSymbol implements Symbol
{
	
	/**
	 * token container/reference
	 * @var Token
	 */
	private $token = null;

	/**
	 * convert symbol data to array
	 * @return array
	 */
	public function toArray() {
		return array();
	}

	/**
	 * get the token
	 * @return Token
	 */
	public function getToken() {
		return $this->token;
	}
	
	/**
	 * set the token
	 * @param Token $token
	 */
	public function setToken(Token $token) {
		$this->token = $token;
	}
	
}