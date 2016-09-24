<?php

namespace PHPagstract\Symbol\Symbols;

use PHPagstract\Name\Names\Name;
use PHPagstract\Token\Tokens\Token as PHPagstractToken;

/**
 * abstract symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractSymbol implements Symbol
{
    
    /**
     * child symbols
     * 
     * @var array[Symbol] 
     */
    private $children;

    /**
     * parent symbol
     * 
     * @var Symbol
     */
    private $parent;

    /**
     * token container/reference
     *
     * @var PHPagstractToken
     */
    private  $token = null;

    /**
     * symbol name
     *
     * @var Name
     */
    private $name = 'Symbol';

    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString() 
    {
        return '';
    }

    /**
     * convert symbol data to array
     *
     * @return array
     */
    public function toArray() 
    {
        $result = array(
                'name' => $this->getName(),
                'line' => null,
                'position' => null
        );
        return $result;
    }

    /**
     * Getter for 'children'.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Setter for 'children'.
     *
     * @param array
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
    
    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }
    
    /**
     * get the token
     *
     * @return PHPagstractToken
     */
    public function getToken() 
    {
        return $this->token;
    }
    
    /**
     * set the token
     *
     * @param PHPagstractToken $token
     */
    public function setToken(PHPagstractToken $token) 
    {
        $this->token = $token;
    }
    
    /**
     * get the name
     *
     * @return Name
     */
    public function getName() 
    {
        return $this->name;
    }
    
    /**
     * set the name
     *
     * @param string $name
     */
    public function setName($name) 
    {
        $this->name = $name;
    }
    
}
