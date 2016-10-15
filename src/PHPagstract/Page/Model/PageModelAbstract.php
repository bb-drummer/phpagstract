<?php
/**
 * page-model abstract
 */
namespace PHPagstract\Page\Model;

use PHPagstract\Exception;
use PHPagstract\Page\Page;
use PHPagstract\Page\PageAbstract;
use PHPagstract\Parser\Parser;
use PHPagstract\Parser\ParserAbstract;
use PHPagstract\Symbol\GenericSymbolizer;
use PHPagstract\Token\AbstractTokenizer;
use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Traits\PageAwareTrait;


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
    use PageAwareTrait;
    
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
     * class contsructor 
     * 
     * @param PageAbstract $page         reference to related page object
     * @param boolean      $throwOnError throw exception on error?
     */
    public function __construct($page = null, $throwOnError = false) 
    {
        if ($page !== null) {
            $this->setPage($page);
        }
        $this->throwOnError = !!($throwOnError);
    }
    
    /**
     * process the content
     * 
     * @return string 
     */
    public function process() 
    {
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
        
        // parse the content and create an abstract symbol/syntax tree
        $result = $parser->parse($content);
        
        return $result;
    }
    
    /**
     * retrieve a (markup) tokenizer instance
     * if not set, initialize new instance
     *
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
     * set tokenizer
     * 
     * @param \PHPagstract\Token\AbstractTokenizer $tokenizer
     */
    public function setTokenizer(AbstractTokenizer $tokenizer) 
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * retrieve a (generic) symbolizer instance
     * if not set, initialize new instance
     *
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
     * the symbolizer
     * 
     * @param GenericSymbolizer $symbolizer
     */
    public function setSymbolizer($symbolizer) 
    {
        $this->symbolizer = $symbolizer;
    }

    /**
     * retrieve parser instance
     * if not set, initialize new instance
     *
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
     * set parser instance
     * 
     * @param \PHPagstract\ParserAbstract $parser
     */
    public function setParser(ParserAbstract $parser) 
    {
        $this->parser = $parser;
    }

    
    
}

