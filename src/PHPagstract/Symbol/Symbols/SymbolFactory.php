<?php

namespace PHPagstract\Symbol\Symbols;

use PHPagstract\Token\Tokens\PagstractAbstractToken;
use PHPagstract\Symbol\Exception\SymbolResolverException;

/**
 * symbol factory class, token <-> symbol mapper
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class SymbolFactory
{
    
    /**
     * generate symbol and map to token
     * 
     * @param PHPagstract\Token\Tokens\PagstractAbstractToken $token
     * @param boolean $throwOnError
     * @return object|boolean
     */
    public static function symbolize( $token, $throwOnError = false ) 
    {
        //echo '<pre>'.htmlentities(print_r($token->getType(), true)).'</pre>';
        
        // get token name "PHPagstactTokenName", fallback "PHPagstactMarkup"
        $symbolName = ucfirst( $token->getType() );
        $symbolClassname = "PHPagstract\\Symbol\\Symbols\\Tokens\\" . $symbolName;
        if (!class_exists($symbolClassname)) {
            //$symbolName = "PagstractMarkup";
            //$symbolClassname = "PHPagstract\\Symbol\\Symbols\\Tokens\\" . $symbolName;
        }
        if (!class_exists($symbolClassname)) {
            if ($throwOnError) {
                throw new SymbolResolverException("Invalid token to symbolize: " . $symbolName);
            }
            return (false);
        }
        
        // create symbol 
        $symbol = new $symbolClassname;
        $symbol->setName($symbolName);
        
        if (method_exists($symbol, "setToken")) {
            $symbol->setToken($token);
        }

        if (method_exists($token, 'hasChildren') && $token->hasChildren()) {
            $tokenChildren = $token->getChildren();
            $symbolChildren = array();
            foreach ($tokenChildren as $idx => $child) {
                $symbolChild = self::symbolize(
                    $child, 
                    $throwOnError
                );
                if ($symbolChild === false) {
                    // Error condition
                    if ($throwOnError) {
                        throw new SymbolResolverException("Could not resolve symbol");
                    }
                    // Error has occurred, so we stop.
                    break;
                }
                $symbolChildren[] = $symbolChild;
            }
            $symbol->setChildren($symbolChildren);
        }
        return $symbol;
    }
    
}

