<?php

namespace PHPagstract\Page;

use PHPagstract\Page\PageModelAbstract;
use PHPagstract\Symbol\GenericSymbolizer;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Token\PropertyReferenceTokenizer;
use PHPagstract\Token\ResourceTokenizer;
use PHPagstract\Token\MessageTokenizer;

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
     * @return string
     */
    public function process() 
    {
        $result = '';
         
        // parse for Pagstract markup
        $result = $this->processMarkup();
         
        // parse for single property references, switch to property resolver
        $result = $this->processPropertyReferences();
        
        // parse for resource references
        $result = $this->processResources();
         
        // parse for resource references
        $result = $this->processMessages();
        
        return $result;
    }
    
    /**
     * @return string
     */
    public function processMarkup() 
    {
        $result = '';
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
         
        $defaultGenericSymbolizer = new GenericSymbolizer($this->throwOnError);
        $parser->setResolver($defaultGenericSymbolizer);
         
        // parse for Pagstract markup
        $pagstarctTokenizer = new PagstractTokenizer($this->throwOnError);
        $parser->setTokenizer($pagstarctTokenizer);
        $result = $parser->parse($content);

        $result = trim($result);
        return $result;
    }
    
    /**
     * @return string
     */
    public function processPropertyReferences() 
    {
        $result = '';
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
         
        // parse for single property references, switch to property resolver
        $propertyTokenizer = new PropertyReferenceTokenizer($this->throwOnError);
        $parser->setTokenizer($propertyTokenizer);
        $result = $parser->parse($content);
        

        $result = trim($result);
        return $result;
    }
    
    /**
     * @return string
     */
    public function processResources() 
    {
        $result = '';
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
         
        // parse for resource references
        $resourceTokenizer = new ResourceTokenizer($this->throwOnError);
        $parser->setTokenizer($resourceTokenizer);
        $resourceResolver = new GenericSymbolizer($this->throwOnError);
        $parser->setResolver($resourceResolver);
        $result = $parser->parse($content);
        
        // set back default resolver
        $defaultGenericSymbolizer = new GenericSymbolizer($this->throwOnError);
        $parser->setResolver($defaultGenericSymbolizer);

        $result = trim($result);
        return $result;
    }
    
    /**
     * @return string
     */
    public function processMessages() 
    {
        $result = '';
        $parser = $this->getParser();
        $content = $this->getPage()->getInputStream();
         
        // parse for resource references
        $messageTokenizer = new MessageTokenizer($this->throwOnError);
        $parser->setTokenizer($messageTokenizer);
        $messageResolver = new GenericSymbolizer($this->throwOnError);
        $parser->setResolver($messageResolver);
        $result = $parser->parse($content);

        // set back default resolver
        $defaultGenericSymbolizer = new GenericSymbolizer($this->throwOnError);
        $parser->setResolver($defaultGenericSymbolizer);
         
        $result = trim($result);
        return $result;
    }
    
    
}
