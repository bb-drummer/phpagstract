<?php
/**
 * PHPagstract DOCTYPE token symbol class
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;

/**
 * PHPagstract DOCTYPE token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class Doctype extends AbstractTokenSymbol
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
        
        $output = '<!DOCTYPE';
        if (count($attr) != 0) {
            foreach ($attr as $key => $value) {
                if ($value === null) {
                    $output .= ' ' . $key;
                } else if (empty($value)) {
                    $output .= ' ' . $key . '=""';
                } else if ($value === true) {
                    $output .= ' ' . $key . '="' . $key . '"';
                } else if ($value === false) {
                    $output .= '';
                } else {
                    $output .= ' ' . $key . '="' . $value . '"';
                }
            }
        } else {
            $output .= ' ' . $this->getValue();
        }
        $output .= '>';
        return $output;
    }

}

