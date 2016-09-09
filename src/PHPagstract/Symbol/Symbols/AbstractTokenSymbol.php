<?php

namespace PHPagstract\Symbol\Symbols;


/**
 * abstract token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractTokenSymbol extends AbstractSymbol {
    
    /**
     * child elements
     * 
     * @var array[Symbol] 
     */
    private $children;

    /**
     */
    public function __construct($parent = null, $throwOnError = false) {
    }

    /**
     * get the token value
     *
     * @return mixed
     */
    public function getValue()
    {
        if (!method_exists($this->getToken(), 'getValue')) {
            return null;
        }
        return $this->getToken()->getValue();
    }
    
    /**
     * get the token attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        if (!method_exists($this->getToken(), 'getAttributes')) {
            return array();
        }
        return $this->getToken()->getAttributes();
    }
    
    /**
     * @return boolean
     */
    public function hasAttributes()
    {    
        if (!method_exists($this->getToken(), 'getAttributes')) {
            return false;
        }
        $attributes = $this->getToken()->getAttributes();
        return !empty($attributes);
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
    
    public function toArray()
    {
        $result = array(
                'name' => $this->getName(),
                'line' => $this->getToken()->getLine(),
                'position' => $this->getToken()->getPosition(),
                'token' => $this->getToken()->getType()
        );

        $attributes = $this->getAttributes();
        if (!empty($attributes)) {
            $result['attributes'] = array();
            foreach ($attributes as $name => $value) {
                $result['attributes'][$name] = $value;
            }
        }

        $children = $this->getChildren();
        if (!empty($children)) {
            $result['children'] = array();
            foreach ($this->children as $child) {
                $result['children'][] = $child->toArray();
            }
        }
    
        return $result;
    }    
}

