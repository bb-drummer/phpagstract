<?php

namespace PHPagstract;

use PHPagstract\Page\PageModelAbstract;
use PHPagstract\Page\PageModel;

/**
 * page generator object abstract
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class PageAbstract
{

    /**
     * page model instance
     *
     * @var PageModelAbstract
     */
    public $pageModel = null;
    
    /**
     */
    public function __construct() 
    {
        
    }
    
    /**
     * initialize page model instance
     * @return PageModelAbstract the $pageModel instance
     */
    public function initPageModel() 
    {
        $name = "";
        $sourcePath = "./";
        $data = array();
        $resources = "./";
        $resources_ext = "./";
        
        $pageModel = new PageModel($name, $sourcePath, $data, $resources, $resources_ext);
        $this->setPageModel($pageModel);
        
        return $this->getPageModel();
    }
    
    /**
     * @return PageModelAbstract the $pageModel instance
     */
    public function getPageModel() 
    {
        return $this->pageModel;
    }

    /**
     * set the $pageModel instance
     * 
     * @param \PHPagstract\Page\PageModelAbstract $pageModel
     */
    public function setPageModel($pageModel) 
    {
        $this->pageModel = $pageModel;
    }

    
}

