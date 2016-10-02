<?php
namespace PHPagstract;

/**
 * a generic collection object for a group of items designed to act similiar to
 * an array.
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class AbstractCollection implements \ArrayAccess, \IteratorAggregate
{

    /**
     * flag if item to add/set must be an instance of $type
     * 
     * @var boolean
     */
    protected $onlyValidType;
    
        
    /**
     * valid type/classname items to add/set must ba an instance of
     * 
     * @var string 
     */
    protected $type;

    
    /**
     * the items
     * 
     * @var array 
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = array();
    }

    /**
     * convert item list/tree to array
     *
     * @return array[mixed]
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->items as $symbol) {
            $result[] = $symbol->toArray();
        }

        return $result;
    }

    /**
     * check is item exists at offset
     * required by the ArrayAccess interface
     * 
     * @param  integer $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * retrieve item at offset
     * required by the ArrayAccess interface
     * 
     * @param  integer $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * set item at offset
     * required by the ArrayAccess interface
     * 
     * @param integer $offset
     * @param mixed   $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->onlyValidType()) {
            $classname = $this->getType();
            if (!$value instanceof $classname) {
                throw new \InvalidArgumentException('Value must be of type '.$classname.'.');
            }
        }
        
        if ($offset === null) {
            $this->items[] = $value;

            return;
        }

        $this->items[$offset] = $value;
    }

    /**
     * unset item at offset
     * required by the ArrayAccess interface
     * 
     * @param integer $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * retrieve item count
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * check for empty item list
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * retrieve array iterator for items
     * required by the IteratorAggregate interface
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
    
    /**
     * set/get 'only valid type' flag
     * 
     * @param  boolean $setTo
     * @return boolean|null $onlyValidType
     */
    public function onlyValidType($setTo = null) 
    {
        if ($setTo === null) {
            return !!$this->onlyValidType;
        }
        $this->onlyValidType = !!$setTo;
    }

    /**
     * retrieve the valid tpye classname
     * 
     * @return string $type
     */
    public function getType() 
    {
        return $this->type;
    }

    /**
     * set  the valid tpye classname
     * 
     * @param string $type
     */
    public function setType($type) 
    {
        $this->type = $type;
    }

    
    
}

