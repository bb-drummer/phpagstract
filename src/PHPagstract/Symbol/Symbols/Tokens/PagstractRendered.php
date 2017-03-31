<?php
/**
 * PHPagstract simple value reference token symbol class
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

/**
 * PHPagstract rendered value token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractRendered extends PagstractMarkup
{
    
    /**
     * class constructor
     * 
     * @param AbstractTokenSymbol $parent
     * @param string              $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        parent::__construct($parent, $throwOnError);
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

