<?php
namespace PHPagstractTest;

/**
 * PHPagstract page class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\Page;
use PHPagstract\PageAbstract;
use PHPagstract\Page\PageModel;

class PageTest extends TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $page = new Page();
            $className = get_class($page);
        } catch (Exception $e) {
            $page = null;
            $className = null;
        }

        $this->assertNotNull($page);
        $this->assertNotNull($className);
        
        $mockPage = $this->createMock("\PHPagstract\Page");
        $this->assertInstanceOf("\PHPagstract\PageAbstract", $mockPage);
        $this->assertInstanceOf("\PHPagstract\Page", $mockPage);
        
    }
    
    public function testSetGetPageModel() 
    {
        
        $page = new Page();

        $model  = $this->getMockForAbstractClass('PHPagstract\\Page\\PageModel', array("my-page.html", "../../data/shop/"));
        $page->setPageModel($model);
        $testModel = $page->getPageModel();

        $this->assertInstanceOf('PHPagstract\\Page\\PageModel', $testModel);
        $this->assertEquals($model, $testModel);
        
    }
    
    public function testInitPageModel() 
    {
        
        $page = new Page();

        $name = "";
        $sourcePath = "./";

        $pageModel = new PageModel($name, $sourcePath);
        $page->initPageModel();
        $testModel = $page->getPageModel();

        $this->assertInstanceOf('PHPagstract\\Page\\PageModel', $testModel);
        $this->assertEquals($pageModel, $testModel);
        
    }

}