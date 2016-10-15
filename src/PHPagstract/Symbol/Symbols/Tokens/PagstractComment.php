<?php

namespace PHPagstract\Symbol\Symbols\Tokens;

/**
 * PHPagstract token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractComment extends PagstractMarkup
{
    
    /**
     */
    public function __construct() 
    {
        parent::__construct();
    }
    
    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString() 
    {
        $result = '<!--- '.$this->getValue().' -->';
        return $result;
    }
}

