<?php

namespace PHPagstract;

use PHPagstract\Symbol\SymbolResolver;
use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Token\AbstractTokenizer;
use PHPagstract\Token\Tokens\TokenCollection;

/**
 * parser object abstract
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class ParserAbstract
{
  
    /** 
     * throw exception on error?
     *
     * @var boolean 
     */
    public $throwOnError;
    
    /**
     * tokenizer container
     *
     * @var AbstractTokenizer
     */
    private $tokenizer = null;
    
    /**
     * symbol resolver container
     *
     * @var PHPagstract\Symbol\SymbolResolver
     */
    private $resolver = null;
    
    
    /**
     * constructor
     */
    public function __construct($tokenizer, $symbolResolver, $throwOnError = false) 
    {
        
        $this->throwOnError = $throwOnError;
        
        //$tokenizer->throwOnError = $throwOnError;
        $this->setTokenizer($tokenizer);

        //$symbolResolver->throwOnError = $throwOnError;
        $this->setResolver($symbolResolver);
        
    }
    
    /**
     * parse the content
     * 
     * @param string $content
     */
    public function parse($content) 
    {
        
        $tokens = $this->tokenize($content);

        $symbols = $this->symbolize($tokens);
        
        $compiled = $this->compile($symbols);
        
        return $compiled;
    }
    
    /**
     * parse the content
     * 
     * @param SymbolCollection $symbols
     */
    public function compile(SymbolCollection $symbols) 
    {
        $compiled = '';
        return $compiled;
    }
    
    /**
     * parse the content
     * 
     * @param  string $content
     * @return TokenCollection
     */
    public function tokenize($content) 
    {
        $tokenizer = $this->getTokenizer();
        $tokens = $tokenizer->parse($content);
        
        return $tokens;
    }
    
    /**
     * map the tokens to symbols
     * 
     * @param  TokenCollection $tokens
     * @return SymbolCollection
     */
    public function symbolize($tokens) 
    {
        $symbolResolver = $this->getResolver();
        
        $symbolTree = $symbolResolver->resolve($tokens);
        
        return $symbolTree;
    }

    /**
     * @return AbstractTokenizer
     */
    public function getTokenizer() 
    {
        return $this->tokenizer;
    }

    /**
     */
    public function setTokenizer($tokenizer) 
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @return the $resolver
     */
    public function getResolver() 
    {
        return $this->resolver;
    }

    /**
     * @param \PHPagstract\PHPagstract\Symbol\SymbolResolver $resolver
     */
    public function setResolver($resolver) 
    {
        $this->resolver = $resolver;
    }

    
    
}

