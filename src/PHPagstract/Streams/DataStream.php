<?php

namespace PHPagstract\Streams;

use PHPagstract\Page\StreamTrait;
use PHPagstract\Page\ScopesTrait;

/**
 * data stream handling
 * 
 * providing setter/getter methods for data stream object
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class DataStream
{
    
    use StreamTrait;
    use ScopesTrait;
    
    /**
     * @param mixed $data
     */
    public function __construct($data = null) 
    {
        if ($data !== null) {
            $this->setStream($data);
        }
    }

    
}

