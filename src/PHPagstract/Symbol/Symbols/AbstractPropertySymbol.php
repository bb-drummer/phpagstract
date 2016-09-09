<?php

namespace PHPagstract\Symbol\Symbols;

/**
 * abstract property symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractPropertySymbol extends AbstractSymbol {
    
    /**
     * property container/reference
     *
     * @var \stdClass
     */
    private $property = null;

    /**
     */
    public function __construct() {
    }

    /**
     * get the property
     *
     * @return \stdClass
     */
    public function getProperty() 
    {
        return $this->property;
    }
    
    /**
     * set the property
     *
     * @param \stdClass $property
     */
    public function setProperty($property) 
    {
        $this->property = $property;
    }
    
}

