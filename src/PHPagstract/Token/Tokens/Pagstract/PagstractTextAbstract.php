<?php

namespace PHPagstract\Token\Tokens;

/**
 * abstract 'Text' token object class, aka. everything that is not a property reference
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractTextAbstract extends PagstractAbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^(.)/", 
			"end" => PHP_EOL
	);
    
	/**
	 * @var boolean 
	 */
	public $nested = false;
    
	/**
	 * token constructor
	 * 
	 * @param string  $type
	 * @param Token  $parent
	 * @param boolean $throwOnError
	 * @param mixed $forcedValue
	 */
	public function __construct($type, Token $parent = null, $throwOnError = false, $forcedValue = null)
	{
		parent::__construct(Token::TEXT, $parent, $throwOnError);

		$this->value = $forcedValue;
	}

	/**
	 * parse for everything that is not a reference
	 * {@inheritDoc}
	 *
	 * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
	 */
	public function parse($html)
	{
	}

	/**
	 * export token data 
	 * {@inheritDoc}
	 *
	 * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::toArray()
	 */
	public function toArray()
	{
		return array(
			'type' => 'text',
			'value' => $this->getValue(),
			'line' => $this->getLine(),
			'position' => $this->getPosition()
		);
	}
}

