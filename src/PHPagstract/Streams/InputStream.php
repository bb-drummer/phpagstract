<?php

namespace PHPagstract\Streams;

use PHPagstract\Traits\StreamTrait;

/**
 * input stream handling
 * 
 * providing setter/getter methods for input stream object
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class InputStream
{
    
    use StreamTrait;
    
    /**
     * @param mixed $input
     */
    public function __construct($input = null) 
    {
        if ($input !== null) {
            $this->setStream($input);
        }
    }
}

