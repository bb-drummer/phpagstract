<?php

namespace PHPagstract\Symbol\Symbols\Tokens;

/**
 * PHPagstract token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractSimpleValue extends PagstractMarkup
{
    
    /**
     */
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function toString() 
    {
        $EOL = $this->config()->EOL();
        $attr = ($this->getAttributes());
        $property = null;
        if (isset($attr['pma:name'])) {
            $property = $this->getPropertyResolver()->getPropertyByReference($attr['pma:name']);
        }
        $propertyValue = '';
        /**
 * @var AbstractPropertySymbol $property 
*/
        if ($property !== null) {
            $propertyValue = $property->getProperty();
        }

        if ($this->config()->debug()) {
            $propertyValue .= '<!-- DEBUG: ' . print_r($this->toArray(), true) . ' -->' . $EOL;
        }
        //$propertyValue .= parent::toString();
        
        return $propertyValue;
    }
}

