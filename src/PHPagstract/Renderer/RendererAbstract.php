<?php
/**
 * renderer object abstract
 */
namespace PHPagstract\Renderer;

use PHPagstract\Traits\PageAwareTrait;

use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\Symbols\Symbol;

/**
 * renderer object abstract class
 * 
 * render the content's symbols and return string representation
 * 
 * @package    PHPagstract
 * @subpackage Renderer
 * @author     Björn Bartels <coding@bjoernbartels.earth>
 * @link       https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright  copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class RendererAbstract
{
    
    use PageAwareTrait;
    
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
     * render the content's symbols and return string representation
     * 
     * @param  SymbolCollection $symbols
     * @return string
     */
    public function render(SymbolCollection $symbols) 
    {
        $rendered = '';
        $symbolsIterator = $symbols->getIterator();
        $symbolsIterator->rewind();
        $symbol = $symbolsIterator->current();
        
        while ($symbol instanceof Symbol) {
            
            if (method_exists($symbol, "setConfiguration")) {
                // apply configuration
                $setConfiguration = $this->getPage()->getConfiguration();
                $symbol->setConfiguration($setConfiguration);
            }
            
            if (method_exists($symbol, "setFilepathResolver")) {
                // reference file/path resolver
                $setFilepathResolver = $this->getPage()->getFilepathResolver();
                $symbol->setFilepathResolver($setFilepathResolver);
            }
            
            if (method_exists($symbol, "setPropertyResolver")) {
                // reference property resolver
                $setPropertyResolver = $this->getPage()->getPropertyResolver();
                $symbol->setPropertyResolver($setPropertyResolver);
            }
            
            if (method_exists($symbol, "setRenderer")) {
                // reference renderer
                $symbol->setRenderer($this);
            }
            
            if (method_exists($symbol, "compile")) {
                // (pre)compile symbol
                $symbol->compile();
            }
            
            if (method_exists($symbol, "toString")) {
                // render symbol output/string representation
                $rendered .= $symbol->toString();
            }
            
            $symbolsIterator->next();
            $symbol = $symbolsIterator->current();
        }
        
        $rendered = trim($rendered);
        return $rendered;
    }
    
}
