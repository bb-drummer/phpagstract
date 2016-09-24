<?php

namespace PHPagstract\Token\Tokens;

/**
 * token abstract class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractToken implements Token
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*<[a-z]/i", 
			"end" => ">"
	);

	/**
	 * @var boolean 
	 */
	protected $nested = false;
    
	/**
	 * @var int 
	 */
	private $depth;

	/**
	 * @var int 
	 */
	private $line;

	/**
	 * @var null|Token 
	 */
	private $parent;

	/**
	 * @var int 
	 */
	private $position;

	/**
	 * @var boolean 
	 */
	protected $throwOnError;

	/**
	 * @var string 
	 */
	private $type;

	/**
	 * Constructor
	 * 
	 * @param string  $type
	 * @param Token   $type
	 * @param boolean $type
	 */
	public function __construct($type, Token $parent = null, $throwOnError = false)
	{
		if (!$this->isValidType($type)) {
			throw new \InvalidArgumentException('Invalid type: '.$type);
		}

		$this->depth = 0;
		if ($parent instanceof Token) {
			$this->depth = $parent->getDepth() + 1;
		}

		$this->line = 0;
		$this->position = 0;
		$this->parent = $parent;
		$this->throwOnError = (boolean) $throwOnError;
		$this->type = $type;
	}

	/**
	 * Getter for 'depth'.
	 * 
	 * @return int the $depth
	 */
	public function getDepth()
	{
		return $this->depth;
	}

	/**
	 * Getter for 'line'.
	 * 
	 * @return int the $depth
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 * Chainable setter for 'line'.
	 * 
	 * @param  int $line
	 * @return self
	 */
	public function setLine($line)
	{
		$this->line = (int) $line;

		return $this;
	}

	/**
	 * Check if current tag/token allows self-closing itself
	 * 
	 * @param  string $html
	 * @return boolean
	 */
	public function isClosingElementImplied($html)
	{
		return false;
	}

	/**
	 * Getter for 'parent'.
	 * 
	 * @return Token the $parent
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Getter for 'position'.
	 * 
	 * @return array the $depth
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Chainable setter for 'position'.
	 * 
	 * @param  int $position
	 * @return self
	 */
	public function setPosition($position)
	{
		$this->position = (int) $position;

		return $this;
	}

	/**
	 * Getter for 'throwOnError'.
	 *
	 * @return boolean
	 */
	protected function getThrowOnError()
	{
		return $this->throwOnError;
	}

	/**
	 * Getter for 'type'.
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Setter for 'type'.
	 *
	 * @param string the $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Check for 'CDATA' type.
	 *
	 * @return boolean
	 */
	public function isCDATA()
	{
		return $this->type === Token::CDATA;
	}

	/**
	 * Check for 'Comment' type.
	 *
	 * @return boolean
	 */
	public function isComment()
	{
		return $this->type === Token::COMMENT;
	}

	/**
	 * Check for 'DocType' type.
	 *
	 * @return boolean
	 */
	public function isDocType()
	{
		return $this->type === Token::DOCTYPE;
	}

	/**
	 * Check for 'Element' type.
	 *
	 * @return boolean
	 */
	public function isElement()
	{
		return $this->type === Token::ELEMENT;
	}

	/**
	 * Check for 'Php' type.
	 *
	 * @return boolean
	 */
	public function isPhp()
	{
		return $this->type === Token::PHP;
	}

	/**
	 * Check for 'Text' type.
	 *
	 * @return boolean
	 */
	public function isText()
	{
		return $this->type === Token::TEXT;
	}

	/**
	 * Check for 'CDATA' type.
	 *
	 * @return boolean
	 */
	public function isPagstract()
	{
		return $this->type === Token::PAGSTRACT;
	}

	/**
	 * Check for a valid given type.
	 *
	 * @param  string
	 * @return boolean
	 */
	protected function isValidType($type)
	{
		return $type === Token::CDATA
			|| $type === Token::COMMENT
			|| $type === Token::DOCTYPE
			|| $type === Token::ELEMENT
			|| $type === Token::PHP
			|| $type === Token::TEXT;
	}
}
