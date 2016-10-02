<?php

namespace PHPagstract;

use PHPagstract\Parser\Exception as ParserException;
use PHPagstract\Symbol\GenericSymbolizer;
use PHPagstract\Symbol\PropertyReferenceSymbolizer;
use PHPagstract\Symbol\Symbols\Symbol;
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
     * @var GenericSymbolizer|PropertyReferenceSymbolizer
     */
    private $resolver = null;
    
    
    /**
     * constructor
     *
     * @param AbstractTokenizer $tokenizer
     * @param GenericSymbolizer $genericSymbolizer
     */
    public function __construct($tokenizer, $genericSymbolizer, $throwOnError = false) 
    {
        
        $this->throwOnError = $throwOnError;
        
        if ($tokenizer instanceof AbstractTokenizer) {
            $this->setTokenizer($tokenizer);
        }

        if ($genericSymbolizer instanceof GenericSymbolizer) {
            $this->setResolver($genericSymbolizer);
        }
        
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
     * comile the content's symbols and return string representation
     * 
     * @param  SymbolCollection $symbols
     * @return string
     */
    public function compile(SymbolCollection $symbols) 
    {
        $compiled = '';
        $symbolsIterator = $symbols->getIterator();
        $symbolsIterator->rewind();
        $symbol = $symbolsIterator->current();
        while ($symbol instanceof Symbol) {
            
            if (method_exists($symbol, "compile")) {
                $compiled .= $symbol->compile();
            }
            
            if (method_exists($symbol, "toString")) {
                $compiled .= $symbol->toString();
            }
            $symbol = $symbolsIterator->next();
        }
        $compiled = trim($compiled);
        return $compiled;
    }
    
    /**
     * tokenize the content
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
     * map tokens to symbols
     * 
     * @param  TokenCollection $tokens
     * @return SymbolCollection
     */
    public function symbolize($tokens) 
    {
        $symbolizer = $this->getResolver();
        
        $symbolTree = $symbolizer->resolve($tokens);
        
        return $symbolTree;
    }

    /**
     * @return AbstractTokenizer
     * @throws ParserException
     */
    public function getTokenizer() 
    {
        if (($this->tokenizer === null) && $this->throwOnError) {
            throw new ParserException("no tokenizer set");
        }
        return $this->tokenizer;
    }

    /**
     *
     * @param AbstractTokenizer $tokenizer
     */
    public function setTokenizer(AbstractTokenizer $tokenizer) 
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @return GenericSymbolizer|PropertyReferenceSymbolizer $resolver
     * @throws ParserException
     */
    public function getResolver() 
    {
        if (($this->resolver === null) && $this->throwOnError) {
            throw new ParserException("no symbol resolver set");
        }
        return $this->resolver;
    }

    /**
     * 
     * @param GenericSymbolizer|PropertyReferenceSymbolizer $resolver
     */
    public function setResolver($resolver) 
    {
        $this->resolver = $resolver;
    }

    
    
}

