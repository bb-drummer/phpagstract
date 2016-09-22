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
        
		// get token name "PHPagstactTokenName", fallback "PHPagstactMarkup"
		$symbolName = ucfirst( $token->getType() );
		$symbolClassname = "PHPagstract\\Symbol\\Symbols\\Tokens\\" . $symbolName;
        
		if (!class_exists($symbolClassname)) {
			if ($throwOnError) {
				throw new SymbolResolverException("Invalid token to symbolize: " . $symbolName);
			}
			return (false);
		}
        
		// create symbol 
		$symbol = new $symbolClassname;
		$symbol->setName($symbolName);
        
		// is token a nesting closing token
		if (method_exists($token, "getName")) {
			$name = $token->getName();
			if ((empty($name) || ($name == $symbolName)) && method_exists($token, "getAttributes")) {
				$attr = $token->getAttributes();
				if (is_array($attr)) {
					$keys = array_keys($attr);
					if ( isset($keys[0]) && is_string($keys[0]) ) {
						$symbol->isClosing(true);
					}
				}
			}
		}
        
		// link current token to symbol
		if (method_exists($symbol, "setToken")) {
			$symbol->setToken($token);
		}

		return $symbol;
	}
    
}

