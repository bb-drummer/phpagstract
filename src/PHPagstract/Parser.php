<?php

namespace PHPagstract;

use PHPagstract\Token\Tokens\TokenCollection;

/**
 * parser object class
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class Parser extends ParserAbstract {
	
	/**
	 * map a token list to symbols
	 * 
	 * @param object|array $root root/main data node
	 * @param object|array $bean current bean data node
	 * @param object|array $context 
	 */
	public function __construct ($root = null, $bean = null, $context = null) {
		parent::__construct ();
	}
	
	/**
	 * parse the content
	 * 
	 * @param string $content
	 */
	public function parse ($content) {
		$tokens = $this->tokenize($content);
		
		$compiled = $this->compile($tokens);
		
		return $compiled;
	}
	
	/**
	 * parse the content
	 * 
	 * @param TokenCollection $tokens
	 */
	public function compile (TokenCollection $tokens) {
		$compiled = '';
		return $compiled;
	}
	
	/**
	 * parse the content
	 * 
	 * @param string $content
	 */
	public function tokenize ($content) {
		$tokens = new TokenCollection();
		return $tokens;
	}
}

