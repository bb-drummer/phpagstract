<?php

namespace PHPagstract\Token\Tokens;

/**
 * PagstractModList 'pma:modList' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractModList extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^\s*<pma:modList[ ]/i", 
            "end" => ">"
    );

    /**
     * @var boolean 
     */
    public $nested = true;
    
    /**
     * token constructor
     * 
     * @param Token   $parent
     * @param boolean $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTMODLIST, $parent, $throwOnError);
        
    }

}
