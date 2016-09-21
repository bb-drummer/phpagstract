<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\PropertyReferenceTokenizer;

/**
 * 'Text' token object class, aka. everything that is not a property reference
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractPropertyReferenceText extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^[^\$]/", 
            "end" => '${'
    );
    
    /**
     * @var boolean 
     */
    public $nested = false;
    
    /**
     * token constructor
     * 
     * @param Token  $parent
     * @param string $throwOnError
     * @param string $forcedValue
     */
    public function __construct(Token $parent = null, $throwOnError = false, $forcedValue = null)
    {
        parent::__construct(Token::PAGSTRACTPROPERTYREFERENCETEXT, $parent, $throwOnError);

        $this->value = $forcedValue;
    }

    /**
     * parse for everything that is not a property reference
     * {@inheritDoc}
     *
     * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
     */
    public function parse($html)
    {
        // Get token position.
        $positionArray = PropertyReferenceTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        // Collapse whitespace before TEXT.
        $startingWhitespace = '';
        if (preg_match("/(^\s)/", $html) === 1) {
            $startingWhitespace = ' ';
        }
        
        $posOfNextElement = mb_strpos($html, '${');
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

