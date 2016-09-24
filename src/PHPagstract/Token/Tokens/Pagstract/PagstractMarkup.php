<?php

namespace PHPagstract\Token\Tokens;

/**
 * PagstractMarkup token object class 
 * 
 * representing any other tag which is not mentioned by one of the other tokens
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMarkup extends PagstractAbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*<[a-z]|^\s*<(?!(\/pma))/i", 
			// another regex : "/(^\s*<[a-z]|^\s*<\/\s*[a-z])|^\s*<(?!(\/pma))/i", 
			"end" => ">"
	);

	/**
	 * @var boolean 
	 */
	public $nested = false;
    
	/**
	 * token constructor
	 * 
	 * @param Token  $parent
	 * @param boolean $throwOnError
	 */
	public function __construct(Token $parent = null, $throwOnError = false)
	{
		parent::__construct(Token::PAGSTRACTMARKUP, $parent, $throwOnError);
        
	}

}
