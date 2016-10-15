<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MessageTokenizer;

/**
 * message reference parsing 'Text' token object class, aka. everything that is not a property reference
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMessageText extends PagstractTextAbstract
{
    /**
     * token constructor
     * 
     * @param Token   $parent
     * @param boolean $throwOnError
     * @param mixed   $forcedValue
     */
    public function __construct(Token $parent = null, $throwOnError = false, $forcedValue = null)
    {
        parent::__construct(Token::PAGSTRACTMESSAGETEXT, $parent, $throwOnError);

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
        $positionArray = MessageTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        $posOfNextElement = mb_strpos($html, 'msg://');
        if ($posOfNextElement === false) {
            $this->value = ($html);

            return '';
        }

        // Find full length of TEXT.
        $text = mb_substr($html, 0, $posOfNextElement);
        $this->value = $text;

        return mb_substr($html, $posOfNextElement);
    }

}

