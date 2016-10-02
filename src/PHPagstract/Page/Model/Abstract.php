<?php

namespace PHPagstract\Page;

use PHPagstract\ParserAbstract;
use PHPagstract\Parser;
use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Symbol\GenericSymbolizer;
use PHPagstract\Token\MessageTokenizer;
use PHPagstract\Exception;
use PHPagstract\Token\AbstractTokenizer;
use PHPagstract\Page;


/**
 * page-model object abstract
 *
 * ...
 * - process markup
 * - process single property references
 * - process resource(_ext) references
 * - process msg references
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class PageModelAbstract
{

    /**
     * page instance
     *
     * @var \PHPagstract\PageAbstract
     */
    protected $page = null;
    
    /**
     * parser instance
     *
     * @var \PHPagstract\ParserAbstract
     */
    protected $parser = null;
    
    /**
     * parser instance
     *
     * @var AbstractTokenizer
     */
    protected $tokenizer = null;
    
    /**
     * parser instance
     *
     * @var GenericSymbolizer
     */
    protected $symbolizer = null;
    
    /**
     * throw exception on error?
     *
     * @var boolean
     */
    public $throwOnError = false;
    
    /**
     * @param boolean $throwOnError throw exception on error?
     */
    public function __construct(Page $page = null, $throwOnError = false) 
    {
        if ($page !== null) {
            $this->setPage($page);
        }
        $this->throwOnError = !!($throwOnError);
    }
    
    /**
     * @return string 
     */
    public function process() 
    {
        $result = '';
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
        
        // simply get the default parsed result here in abstract class
        $result = $parser->parse($content);
        
        $result = trim($result);
        return $result;
    }
    
    /**
     * @return PageAbstract $page
     */
    public function getPage() 
    {
        if ($this->page === null && $this->throwOnError === true) {
            throw new Exception();
        }
        return $this->page;
    }

    /**
     * @param \PHPagstract\PageAbstract $page
     */
    public function setPage($page) 
    {
        $this->page = $page;
    }
    
    /**
     * @return \PHPagstract\Token\AbstractTokenizer $tokenizer
     */
    public function getTokenizer() 
    {
        if (!($this->tokenizer instanceof AbstractTokenizer)) {
            $tokenizer = new MarkupTokenizer($this->throwOnError);
            $this->setTokenizer($tokenizer);
        }
        return $this->tokenizer;
    }

    /**
     * @param \PHPagstract\Token\AbstractTokenizer $tokenizer
     */
    public function setTokenizer(AbstractTokenizer $tokenizer) 
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @return \PHPagstract\Symbol\GenericSymbolizer $symbolizer
     */
    public function getSymbolizer() 
    {
        if (!($this->symbolizer instanceof GenericSymbolizer)) {
            $symbolizer = new GenericSymbolizer($this->throwOnError);
            $this->setSymbolizer($symbolizer);
        }
        return $this->symbolizer;
    }

    /**
     * @param \PHPagstract\Symbol\GenericSymbolizer|\PHPagstract\Symbol\PropertyReferenceSymbolizer $symbolizer
     */
    public function setSymbolizer($symbolizer) 
    {
        $this->symbolizer = $symbolizer;
    }

    /**
     * @return \PHPagstract\Parser $parser
     */
    public function getParser() 
    {
        if (!($this->parser instanceof ParserAbstract)) {
            $tokenizer  = $this->getTokenizer();
            $symbolizer = $this->getSymbolizer();
            $parser = new Parser($tokenizer, $symbolizer, $this->throwOnError);
            $this->setParser($parser);
        }
        
        return $this->parser;
    }

    /**
     * @param \PHPagstract\ParserAbstract $parser
     */
    public function setParser(ParserAbstract $parser) 
    {
        $this->parser = $parser;
    }

    
    
}

