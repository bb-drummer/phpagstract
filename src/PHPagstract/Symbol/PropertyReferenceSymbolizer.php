<?php
namespace PHPagstract\Symbol;

use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;
use PHPagstract\Symbol\Symbols\Properties\ListProperty;
use PHPagstract\Symbol\Symbols\Properties\ObjectProperty;
use PHPagstract\Symbol\Symbols\Properties\RootProperty;
use PHPagstract\Symbol\Symbols\Properties\ScalarProperty;

/**
 * PHPagstract property reference resolver class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyReferenceSymbolizer
{
    
    /**
     * throw exception on error?
     * 
     * @var boolean 
     */
    protected $throwOnError;
    
    /**
     * @param boolean $throwOnError throw exception on error?
     */
    public function __construct($throwOnError = false) 
    {
        $this->throwOnError = $throwOnError;
    }
    
    /**
     * symbolize (json) data into special (Pagstract) data types
     * 
     * @param  mixed                  $data
     * @param  AbstractPropertySymbol $parent
     * @param  string name
     * @return AbstractPropertySymbol|null
     */
    public function symbolize($data, AbstractPropertySymbol $parent = null, $name = null) 
    {
        
        if (($parent === null)) {
            // no parent, so create a root node
            $root = new RootProperty();
            if (isset($data->root)) {
                    $data = $data->root;
            }
            $rootProperties = get_object_vars($data);
            $properties = [];
            foreach ($rootProperties as $name => $value) {
                $properties[$name] = $this->symbolize($value, $root, $name);
            }
                $root->set('properties', (object) $properties);
            return ($root);
        }
        
        switch (true) {
            
            case $this->isNull($data):
                // set null property type
                $property = new ScalarProperty($name, $parent);
                $property->setProperty($data);
                break;
            
            case $this->isList($data): 
                // set list property type
                // try to detect a component?
                $property = new ListProperty($name, $parent);
                $items = [];
                foreach ($data as $idx => $item) {
                    $items[] = $this->symbolize($item, $property, $idx);
                }
                $property->set('items', $items);
                break;
            
            case $this->isObject($data): 
                // set object property type
                // try to detect a component?
                $property = new ObjectProperty($name, $parent);
                $objProperties = get_object_vars($data);
                $properties = [];
                foreach ($objProperties as $xname => $value) {
                    $properties[$xname] = $this->symbolize($value, $property, $xname);
                }
                $property->set('properties', (object) $properties);
                break;

            case $this->isScalar($data):
            default: 
                // set scalar property type
                // try to detect component
                $property = new ScalarProperty($name, $parent);
                $property->setProperty($data);
            
        }
        
        return $property;
    }
    
    
    /**
     * check for value type 'object'
     * 
     * @param  mixed $param
     * @return boolean
     */
    public function isObject($param) 
    {
        return ($param instanceof \stdClass);
    }


    /**
     * check for value type 'scalar' (string, int, float, bool)
     *
     * @param  mixed $param
     * @return boolean
     */
    public function isScalar($param) 
    {
        return (is_int($param) || is_float($param) || is_string($param) || is_bool($param));
    }


    /**
     * check for value type 'null'
     *
     * @param  mixed $param
     * @return boolean
     */
    public function isNull($param) 
    {
        return ($param === null);
    }


    /**
     * check for value type 'list'
     *
     * @param  mixed $param
     * @return boolean
     */
    public function isList($param) 
    {
        return (is_array($param));
    }
    
}
