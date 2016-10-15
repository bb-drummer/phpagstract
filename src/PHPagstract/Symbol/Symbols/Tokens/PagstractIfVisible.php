<?php

namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;

/**
 * PHPagstract token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractIfVisible extends PagstractMarkup
{
    
    /**
     * resolved property is visible?
     *
     * @var boolean
     */
    protected $isVisible = true;
    
    /**
     */
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function compile() 
    {

        $attr = ($this->getAttributes());
        $property = null;
        if (isset($attr['pma:name'])) {
            $property = $this->getPropertyResolver()->getPropertyByReference($attr['pma:name']);
        }
        /**
 * @var AbstractPropertySymbol $property 
*/
        //echo '<pre>resolved: '.htmlentities(print_r( $property, true )).'</pre>'; flush();
        if ($property !== null) {
            $propertyValue = ($property->getProperty() !== null);
            if ($property->getType() == 'list' ) {
                $items = $property->get('items');
                $propertyValue = !empty($items);
            } else if ($property->getType() == 'object' ) {
                $items = array_keys(get_object_vars($property->get('properties')));
                $propertyValue = !empty($items);
            }
        } else {
            $propertyValue = false;
        }
        
        $this->isVisible = $propertyValue;
        if (isset($attr['pma:condition']) && ($attr['pma:condition'] == 'false') && ($propertyValue === true) ) {
            $this->isVisible = false;
        }
        if (isset($attr['pma:condition']) && ($attr['pma:condition'] != 'false') && ($propertyValue === false) ) {
            $this->isVisible = false;
        }
        if (isset($attr['pma:condition']) && ($attr['pma:condition'] == 'false') && ($propertyValue === false) ) {
            $this->isVisible = true;
        }
        if (isset($attr['pma:condition']) && ($attr['pma:condition'] != 'false') && ($propertyValue === true) ) {
            $this->isVisible = true;
        }
    }
    
    public function toString() 
    {
        $EOL = $this->config()->EOL();
        $attr = ($this->getAttributes());
        $result = '';
        if (!$this->isVisible) {
            if ($this->config()->debug()) {
                $result .= '<!-- DEBUG: property "' . $attr['pma:name'] . '" not visible -->' . $EOL;
            }
            return $result;
        }

        if ($this->config()->debug()) {
            $result .= '<!-- DEBUG: property "' . $attr['pma:name'] . '" visible -->' . $EOL;
        }
        
        if ($this->hasChildren()) {
            $children = $this->getChildren();
            $result .= $this->getRenderer()->render($children) . $EOL;
        }
        
        return $result;
    }
    
}

