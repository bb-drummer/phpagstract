<?php
/**
 * PHPagstract data property reference text symbol class
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;

/**
 * PHPagstract data property reference text symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractPropertyReferenceText extends AbstractTokenSymbol
{
    
    /**
     * class constructor
     * 
     * @param AbstractTokenSymbol $parent
     * @param string              $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        parent::__construct($parent, $throwOnError);
    }

    /**
     * compile symbol to string representation
     *
     * @return string
     */
    public function compile()
    {
        return ''; // $this->toString();
    }
    
    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString() 
    {
        $result = $this->getValue();
        return $result;
    }
}

