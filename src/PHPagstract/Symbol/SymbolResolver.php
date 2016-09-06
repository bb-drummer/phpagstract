<?php

namespace PHPagstract\Symbol;


use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\Symbols\SymbolFactory;
use PHPagstract\Symbol\Exception\SymbolResolverException;

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
     * token tree/list
     *
     * @var TokenCollection
     */
    protected static $tokenTree = null;
    
    /**
     */
    public function __construct($throwOnError = false) 
    {
        $this->throwOnError = !!$throwOnError;
    }
    
    /**
     * map tokens to symbols
     *
     * @param  \PHPagstract\Token\Tokens\TokenCollection $tokens
     * @return SymbolCollection
     */
    public function resolve($tokens) 
    {

        $this->setTokenTree($tokens);
        $symbols = new SymbolCollection();
        
        $tokenTree = $this->getTokenTree()->getIterator();
        if ($tokenTree !== null) {
            $tokenTree->rewind();
            $currentToken = $tokenTree->current();
            while ($currentToken) {
                
                $symbol = SymbolFactory::symbolize(
                    $currentToken,
                    $this->throwOnError
                );
                
                if ($symbol === false) {
                    // Error condition
                    if ($this->throwOnError) {
                        throw new SymbolResolverException("Could not resolve symbol");
                    }
                    // Error has occurred, so we stop.
                    break;
                } else {
                    
                    $symbols[] = $symbol;
                }
                
                $currentToken = $tokenTree->next();
                
            };
        }
        
        return $symbols;
        
    }
    
    /**
     * set current tokens
     * 
     * @param \PHPagstract\Token\Tokens\TokenCollection $tokens
     */
    public function setTokenTree($tokens) 
    {
        self::$tokenTree = $tokens;
    }
    
    /**
     * get curent tokens

     * @return \PHPagstract\Token\Tokens\TokenCollection
     */
    public function getTokenTree() 
    {
        return self::$tokenTree;
    }
    
}

