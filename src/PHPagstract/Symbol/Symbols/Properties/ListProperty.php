<?php

namespace PHPagstract\Symbol\Symbols\Properties;

use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;

/**
 * list property symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class ListProperty extends AbstractPropertySymbol
{
    
    /**
     */
    public function __construct($name, $parent) 
    {
        parent::__construct('list', $name, $parent);
    }
}

