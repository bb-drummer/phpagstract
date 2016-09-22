<?php
namespace PHPagstract\Symbol;


use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\Symbols\SymbolFactory;
use PHPagstract\Token\Tokens\TokenCollection;

/**
 * symbol resolver object class
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class SymbolResolver
{
	/**
	 * @var boolean 
	 */
	protected $throwOnError;
    
	/**
	 */
	public function __construct($throwOnError = false) 
	{
		$this->throwOnError = $throwOnError;
	}
    
	/**
	 * map tokens to symbols
	 *
	 * @param  \PHPagstract\Token\Tokens\TokenCollection $tokens
	 * @return SymbolCollection
	 */
	public function resolve($tokens) 
	{

		$symbols = new SymbolCollection();

		$tokenTree = $tokens->getIterator();
		if ($tokenTree !== null) {
			$tokenTree->rewind();
			$currentToken = $tokenTree->current();
			while ($currentToken) {
				$symbol = SymbolFactory::symbolize(
					$currentToken,
					$this->throwOnError
				);
				if ($symbol === false) {
					// Error condition ? 
					// maybe add a fallback here ?!
                
					// Error has occurred, so we stop.
					break;
				}
                
				if (method_exists($currentToken, 'hasChildren') && $currentToken->hasChildren()) {
					$tokenChildren = $currentToken->getChildren();
					$children = new TokenCollection();
					$symbolChildren = array();
					foreach ($tokenChildren as $idx => $child) {
						$children[] = $child;
					}
					$symbolChildren = $this->resolve($children);
					$symbol->setChildren($symbolChildren);
				}
		        
				$symbols[] = $symbol;
                
				$tokenTree->next();
				$currentToken = $tokenTree->current();
                
			};
		}
        
		return $symbols;
        
	}
    
}
