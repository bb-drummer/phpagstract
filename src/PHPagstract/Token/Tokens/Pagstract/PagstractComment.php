<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Token\Exception\TokenizerException;

/**
 * 'Comment' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractComment extends PagstractAbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*<!---/", 
			"end" => "-->"
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
		parent::__construct(Token::PAGSTRACTCOMMENT, $parent, $throwOnError);

		$this->value = null;
	}

	/**
	 * parse for comment
	 * {@inheritDoc}
	 *
	 * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
	 */
	public function parse($html)
	{
		$html = ltrim($html);

		// Get token position.
		$positionArray = MarkupTokenizer::getPosition($html);
		$this->setLine($positionArray['line']);
		$this->setPosition($positionArray['position']);

		// Parse token.
		$posOfEndOfComment = mb_strpos($html, '-->');
		if ($posOfEndOfComment === false) {
			if ($this->getThrowOnError()) {
				throw new TokenizerException('Invalid comment.');
			}

			return '';
		}

		$this->value = trim(mb_substr($html, 5, $posOfEndOfComment - 5));

		return mb_substr($html, $posOfEndOfComment + 3);
	}
}
