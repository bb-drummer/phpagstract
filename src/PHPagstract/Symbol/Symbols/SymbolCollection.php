<?php

namespace PHPagstract\Symbol\Symbols;

/**
 * A SymbolCollection is a group of symbols designed to act similiar to
 * an array.
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class SymbolCollection implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array[Symbol] 
     */
    private $symbols;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->symbols = array();
    }

    /**
     * convert symbol list/tree to array
     *
     * @return array[Symbol]
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->symbols as $symbol) {
            $result[] = $symbol->toArray();
        }

        return $result;
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->symbols);
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetGet($offset)
    {
        return $this->symbols[$offset];
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Symbol) {
            throw new \InvalidArgumentException('Value must be of type Symbol.');
        }

        if ($offset === null) {
            $this->symbols[] = $value;

            return;
        }

        $this->symbols[$offset] = $value;
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetUnset($offset)
    {
        unset($this->symbols[$offset]);
    }

    public function count()
    {
        return count($this->symbols);
    }

    public function isEmpty()
    {
        return empty($this->symbols);
    }

    /**
     * Required by the IteratorAggregate interface.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->symbols);
    }
}
