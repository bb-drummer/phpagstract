<?php

namespace PHPagstract;

/**
 * page generator object class
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class Page extends PageAbstract
{
    
    /**
     * parser instance
     *
     * @var ParserAbstract
     */
    public $parser = null;
    
    /**
     */
    public function __construct() 
    {
        parent::__construct();
        
    }
    
    /**
     * @return the $parser
     */
    public function getParser() 
    {
        return $this->parser;
    }

    /**
     * @param \PHPagstract\ParserAbstract $parser
     */
    public function setParser($parser) 
    {
        $this->parser = $parser;
    }

    
}

