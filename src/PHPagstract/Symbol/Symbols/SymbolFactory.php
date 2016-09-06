<?php

namespace PHPagstract\Symbol\Symbols;

use PHPUnit\Framework\TestCase;

/**
 *
 * @author bba
 */
class SymbolFactory extends TestCase
{
    
    
    public static function symbolize( $token, $throwOnError = false ) 
    {
        
        // get token name "PHPagstactTokenName", fallback "PHPagstactMarkup"
        
        // symbol class = "PHPagstract\\Symbol\\Symbols\\{TokenName}"
        
        // create symbol 
        
        if (!isset($token->type)) {
            $test = new TestCase();
            $symbol = $test->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
            
            return $symbol;
        }
        
        return (false);
    }
    
}

