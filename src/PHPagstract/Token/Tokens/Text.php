<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MarkupTokenizer;

/**
 * 'Text' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class Text extends AbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/^[^<]/", 
			"end" => "<"
	);
	
	/** @var string */
    private $value;

    public function __construct(Token $parent = null, $throwOnError = false, $forcedValue = null)
    {
        parent::__construct(Token::TEXT, $parent, $throwOnError);

        $this->value = $forcedValue;
    }

    public function parse($html)
    {
        // Get token position.
        $positionArray = MarkupTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        // Collapse whitespace before TEXT.
        $startingWhitespace = '';
        if (preg_match("/(^\s)/", $html) === 1) {
            $startingWhitespace = ' ';
        }
		$textEnd = (get_class($this))::$matching["end"];
        $posOfNextElement = mb_strpos($html, $textEnd);
        if ($posOfNextElement === false) {
            $this->value = $startingWhitespace . trim($html);

            return '';
        }

        // Find full length of TEXT.
        $text = mb_substr($html, 0, $posOfNextElement);
        if (trim($text) == '') {
            $this->value = ' ';

            return mb_substr($html, $posOfNextElement);
        }

        // Collapse whitespace after TEXT.
        $endingWhitespace = '';
        if (preg_match("/(\s$)/", $text) === 1) {
            $endingWhitespace = ' ';
        }

        $this->value = $startingWhitespace . trim($text) . $endingWhitespace;

        return mb_substr($html, $posOfNextElement);
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
            'type' => 'text',
            'value' => $this->value,
            'line' => $this->getLine(),
            'position' => $this->getPosition()
        );
    }
}

