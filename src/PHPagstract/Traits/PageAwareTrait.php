<?php
/**
 * related page object awareness trait
 */
namespace PHPagstract\Traits;

use PHPagstract\Exception;
use PHPagstract\Page\Page;

/**
 * related page object awareness trait
 *
 * providing setter/getter methods for (basic) page building configuration
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait PageAwareTrait
{

    /**
     * page instance
     *
     * @var \PHPagstract\PageAbstract
     */
    protected $page = null;
    
    /**
     * retrieve related page instance
     * 
     * @return PHPagstract\Page\PageAbstract
     * @throws PHPagstract\Exception
     */
    public function getPage() 
    {
        if ($this->page === null && $this->throwOnError === true) {
            throw new Exception("no page relation set");
        }
        return $this->page;
    }

    /**
     * set related page instance
     * 
     * @param \PHPagstract\Page\PageAbstract $page
     */
    public function setPage($page) 
    {
        $this->page = $page;
    }
}

