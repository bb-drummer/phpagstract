<?php
/**
 * page-model object
 */
namespace PHPagstract\Page\Model;

use PHPagstract\Page\Model\PageModelAbstract;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Symbol\Symbols\SymbolCollection;

/**
 * page-model object class 
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PageModel extends PageModelAbstract
{
    
    /**
     * process the content into an abstract symbol/syntax tree
     * 
     * @return SymbolCollection
     */
    public function process() 
    {
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();

        // parse for Pagstract markup
        $pagstarctTokenizer = new PagstractTokenizer($this->throwOnError);
        $parser->setTokenizer($pagstarctTokenizer);
        $result = $parser->parse($content);
    
        return $result;
    }
    
}
