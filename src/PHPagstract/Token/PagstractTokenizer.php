<?php

namespace PHPagstract\Token;

use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\Tokens\Element;

/**
 * markup tokenizer object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractTokenizer extends MarkupTokenizer
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

        Element::$nested = false;
        
        TokenFactory::clearMatchings();
        TokenFactory::registerMatching("Php");
        TokenFactory::registerMatching("Comment");
        TokenFactory::registerMatching("CData");
        TokenFactory::registerMatching("DocType");
        TokenFactory::registerMatching("Pagstract", "/^\s*<pma|^\s*<object |^\s*<a |^\s*<area |^\s*<input |^\s*<select /i", ">");
        TokenFactory::registerMatching("Element", "/^\s*<[a-z]|^\s*<(?!(\/pma))/i", ">");
        TokenFactory::registerMatching("Text");
    }

}
