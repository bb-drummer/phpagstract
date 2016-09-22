<?php

namespace PHPagstract\Token;

use PHPagstract\Token\Tokens\TokenCollection;

/**
 * abstract tokenizer object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractTokenizer
{

    /**
     */
    public function __construct() 
    {
    }
    
    /**
     * Will parse html into tokens.
     *
     * @param string $html string The HTML to tokenize.
     *
     * @return TokenCollection
     */
    public function parse($html) 
    { 
        return ((new TokenCollection())); 
    }
    
    /**
     * get position array (line, position)
     * 
     * @return array
     */
    public static function getPosition($html) 
    { 
        return array(
            'line' => 0,
            'position' => 0
        );
    }
    
}
