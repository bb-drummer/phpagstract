<?php

namespace PHPagstract\Token\Tokens;

/**
 * PagstractSimpleValue 'pma:value' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMessage extends PagstractAbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*msg:\/\//i", 
			"end" => PHP_EOL
	);

    /** @var boolean */
    public static $nested = false;
	
	/**
	 * token constructor
	 * 
	 * @param Token $parent
	 * @param string $throwOnError
	 */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTMESSAGE, $parent, $throwOnError);

        $this->name = null;
        $this->value = null;

        $this->attributes = array();
        $this->children = array();
    }

}
