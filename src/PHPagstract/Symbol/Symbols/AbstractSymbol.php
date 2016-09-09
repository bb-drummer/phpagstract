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
     * token container/reference
     *
     * @var PHPagstractToken
     */
    private $token = null;

	/**
     * symbol name
     *
     * @var Name
     */
    private $name = 'Symbol';

    /**
     * convert symbol to string representation
     *
     * @return array
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
     * @param PHPagstract\Token\Tokens\Token $token
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
