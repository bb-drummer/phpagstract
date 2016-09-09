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
class PagstractBean extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^\s*<pma:bean[ ]/i", 
            "end" => ">"
    );

    /**
     * @var boolean 
     */
    public static $nested = true;
    
    /**
     * token constructor
     * 
     * @param Token  $parent
     * @param string $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTBEAN, $parent, $throwOnError);

        $this->name = null;
        $this->value = null;

        $this->attributes = array();
        $this->children = array();
    }

}
