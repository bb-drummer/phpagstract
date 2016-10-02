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
abstract class AbstractTokenSymbol extends AbstractSymbol
{
    
    /**
     * is closing tag?
     * 
     * @var boolean 
     */
    private $closing;
    
    /**
     * throw exception on error?
     * 
     * @var boolean 
     */
    protected $throwOnError;

    /**
     * class contsructor 
     * 
     * @param null|Symbol  $parent
     * @param boolean $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        if ($parent !== null) {
            $this->parent = $parent;
        }
        $this->throwOnError = !!($throwOnError);
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
     * @return null|array
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
    
    public function toArray()
    {
        $result = array(
                'name' => $this->getName(),
                'line' => $this->getToken()->getLine(),
                'position' => $this->getToken()->getPosition(),
                'token' => $this->getToken()->getType(),
        );

        if ($this->isClosing()) {
            $result['closing'] = true;
        }
        
        $result['token'] = $this->getToken()->toArray();
        
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
            foreach ($children as $child) {
                $result['children'][] = $child->toArray();
            }
        }
    
        return $result;
    }    

    /**
     * Setter and getter for 'children'.
     *
     * @param  boolaan
     * @return boolean
     */
    public function isClosing($isClosing = null)
    {
        if ($isClosing !== null) {
            $this->closing = !!$isClosing;
        }
        return !!$this->closing;
    }
    
}

