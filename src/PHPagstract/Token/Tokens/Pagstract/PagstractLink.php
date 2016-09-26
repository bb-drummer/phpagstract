<?php

namespace PHPagstract\Token\Tokens;

/**
 * PagstractLink '<a pma:name="..." ' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractLink extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^\s*<a[ ]/i", 
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
     * @param boolean $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTLINK, $parent, $throwOnError);
        
    }

}
