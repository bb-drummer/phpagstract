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
class Comment extends AbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*<!--/", 
			"end" => "-->"
	);
    
	/**
	 * @var string 
	 */
	private $value;

	public function __construct(Token $parent = null, $throwOnError = false)
	{
		parent::__construct(Token::COMMENT, $parent, $throwOnError);

		$this->value = null;
	}

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

		$this->value = trim(mb_substr($html, 4, $posOfEndOfComment - 4));

		return mb_substr($html, $posOfEndOfComment + 3);
	}

	/**
	 * Getter for 'value'.
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function toArray()
	{
		return array(
			'type' => 'comment',
			'value' => $this->value,
			'line' => $this->getLine(),
			'position' => $this->getPosition()
		);
	}
}
