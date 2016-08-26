<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Token\Exception\ParseException;

/**
 * 'DocType' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class DocType extends AbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^\s*<!DOCTYPE /i", 
			"end" => ">"
	);
	
	/** @var string */
    private $value;

    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::DOCTYPE, $parent, $throwOnError);

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
        $posOfEndOfDocType = mb_strpos($html, '>');
        if ($posOfEndOfDocType === false) {
            if ($this->getThrowOnError()) {
                throw new ParseException('Invalid DOCTYPE.');
            }

            return '';
        }

        $this->value = trim(mb_substr($html, 10, $posOfEndOfDocType - 10));

        return mb_substr($html, $posOfEndOfDocType + 1);
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
            'type' => 'doctype',
            'value' => $this->value,
            'line' => $this->getLine(),
            'position' => $this->getPosition()
        );
    }
}
