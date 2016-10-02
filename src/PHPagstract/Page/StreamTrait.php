<?php

namespace PHPagstract\Page;

/**
 * stream handling trait
 * 
 * providing setter/getter methods for data stream object property
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait StreamTrait
{
    
    /**
     * data stream
     *
     * @var mixed
     */
    public $stream = null;
    
    /**
     * return current stream data as a reference to it's container
     * 
     * @return mixed $stream
     */
    public function getStream() 
    {
        $streamReference =& $this->stream;
        return $streamReference;
    }

    /**
     * set stream data
     *
     * @param mixed $stream
     */
    public function setStream($stream) 
    {
        $this->stream = $stream;
    }



}

