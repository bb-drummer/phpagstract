<?php

namespace PHPagstract\Token\Tokens;

/**
 * A TokenCollection is a group of tokens designed to act similiar to
 * an array.
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class TokenCollection implements \ArrayAccess, \IteratorAggregate
{
    /** @var array[Token] */
    private $tokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokens = array();
    }

    public function toArray()
    {
        $result = array();
        foreach ($this->tokens as $token) {
            $result[] = $token->toArray();
        }

        return $result;
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->tokens);
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetGet($offset)
    {
        return $this->tokens[$offset];
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Token) {
            throw new \InvalidArgumentException('Value must be of type Token.');
        }

        if ($offset === null) {
            $this->tokens[] = $value;

            return;
        }

        $this->tokens[$offset] = $value;
    }

    /**
     * Required by the ArrayAccess interface.
     */
    public function offsetUnset($offset)
    {
        unset($this->tokens[$offset]);
    }

    public function count()
    {
        return count($this->tokens);
    }

    public function isEmpty()
    {
        return empty($this->tokens);
    }

    /**
     * Required by the IteratorAggregate interface.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tokens);
    }
}
