<?php

namespace PHPagstract\Symbol;


use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\Symbols\SymbolFactory;
use PHPagstract\Symbol\Exception\SymbolResolverException;
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
     * token tree/list
     *
     * @var TokenCollection
     */
    protected static $tokenTree = null;
    
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
                    // Error condition ? 
                    // maybe add a fallback here ?!
                
                    // Error has occurred, so we stop.
                    break;
                }
                
                $symbols[] = $symbol;
                
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
        if ( !($tokens instanceof  \PHPagstract\Token\Tokens\TokenCollection)) {
            if ($this->throwOnError) {
                throw new SymbolResolverException('Invalid token-collection to set to symbolize');
            }
            self::$tokenTree = new TokenCollection();
        } else {
            self::$tokenTree = $tokens;
        }
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
