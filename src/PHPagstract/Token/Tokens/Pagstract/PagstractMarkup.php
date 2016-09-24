<?php

namespace PHPagstract\Token\Tokens;

/**
 * PagstractSimpleValue 'pma:value' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMarkup extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^\s*<[a-z]|^\s*<(?!(\/pma))/i", 
            //"start" => "/(^\s*<[a-z]|^\s*<\/\s*[a-z])|^\s*<(?!(\/pma))/i", 
            "end" => ">"
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
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTMARKUP, $parent, $throwOnError);
        
    }

}
