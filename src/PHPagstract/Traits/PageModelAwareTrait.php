<?php
/**
 * related page model instance awareness trait
 */
namespace PHPagstract\Traits;

use PHPagstract\Exception;
use PHPagstract\Page\Model\PageModel;

/**
 * related page object awareness trait
 *
 * providing setter/getter methods for (basic) page model instance
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait PageModelAwareTrait
{

    /**
     * page instance
     *
     * @var PHPagstract\Page\Model\PageModel
     */
    protected $pageModel = null;
    
    /**
     * retrieve related page model instance
     * 
     * @return PHPagstract\Page\Model\PageModel
     * @throws PHPagstract\Exception
     */
    public function getPageModel() 
    {
        if ($this->pageModel === null && $this->throwOnError === true) {
            throw new Exception("no page model set");
        }
        return $this->pageModel;
    }

    /**
     * set related page model instance
     * 
     * @param PHPagstract\Page\Model\PageModel $page
     */
    public function setPageModel($pageModel) 
    {
        $this->pageModel = $pageModel;
    }
}

