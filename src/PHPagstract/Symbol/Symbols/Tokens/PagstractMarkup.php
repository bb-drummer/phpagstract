<?php
/**
 * PHPagstract generic markup token symbol
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;
use PHPagstract\Traits\PropertyResolverAwareTrait;

/**
 * PHPagstract generic markup token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMarkup extends AbstractTokenSymbol
{
    use PropertyResolverAwareTrait;
    
    /**
     * class constructor
     * 
     * @param unknown $parent
     * @param string $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        parent::__construct($parent, $throwOnError);
    }

    /**
     * compile symbol
     * prepare stuff, resolve properties... whatever
     *
     * @return string
     */
    public function compile()
    {
        return ''; // $this->toString();
    }
    
    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString() 
    {   
        return $this->buildTag();
    }
    
    public function buildTag() 
    {
        $EOL = $this->config()->EOL();
        if ($this->isClosing() ) {
            // build a simple closing tag and return early
            $attr = array_keys($this->getAttributes());
            $result = '</' . $attr[0] . '>' . '';
            return $result;
        }
        
        // create opening tag
        $attr = ($this->getAttributes());
        $result = $EOL . '<'.$this->getToken()->getName();
        if (count($attr) > 0) {
            foreach ($attr as $key => $value) {
                // create attribute
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }
        $result .='>' . '';
        if ($this->config()->debug()) {
            $result .= '<!-- DEBUG: ' . print_r($this->toArray(), true) . ' -->' . $EOL;
        }


        // well, this special markup symbol actually does NOT have any children
        // in its common sence, so we leave this out for now
        //
        // if ($this->hasChildren()) {
        //     $children = $this->getChildren();
        //     $result .= $this->getRenderer()->render($children) . $EOL;
        // }
        
        return $result;
    }
}

