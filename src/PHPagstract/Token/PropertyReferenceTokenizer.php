<?php

namespace PHPagstract\Token;

use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\Tokens\Element;
use PHPagstract\Token\Tokens\Token;
use PHPagstract\Token\Tokens\PagstractMarkup;

/**
 * markup tokenizer object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyReferenceTokenizer extends MarkupTokenizer
{
    /** @var boolean */
    private $throwOnError;

    /** @var string */
    private static $allHtml = '';

    /**
     * Constructor
     */
    public function __construct($throwOnError = false)
    {
        $this->throwOnError = (boolean) $throwOnError;

        //PagstractMarkup::$nested = false;
        
        TokenFactory::clearMatchings();
        
        TokenFactory::registerMatching("Text", "/^[^\$]/", '${');

        TokenFactory::registerMatching(Token::PAGSTRACTPROPERTYREFERENCE);
    }

}
