<?php
/**
 * PHPagstract page class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;

use PHPagstract\Page\PageModel;
use PHPagstract\Page\PageModelAbstract;

class PageModelTest extends TestCase
{
    public function testInstantiateObject()
    {
    	try {
    		$model = new PageModel(null, null, null);
    		$className = get_class($model);
    	} catch (Exception $e) {
    		$tpl = null;
    		$className = null;
    	}

    	$this->assertNotNull($model);
    	$this->assertNotNull($className);
    	
		$mockPageModel = $this->createMock("\PHPagstract\Page\PageModel");
    	$this->assertInstanceOf("\PHPagstract\Page\PageModelAbstract", $mockPageModel);
    	$this->assertInstanceOf("\PHPagstract\Page\PageModel", $mockPageModel);
    }
    
    public function testSetGetName () {
    	$model = new PageModel(null, null, null);
    	$model->setName("my-name");
    	$name = $model->getName();
    	$this->assertEquals("my-name", $name);
    }
    
    public function testSetGetSourcespath () {
    	$model = new PageModel(null, null, null);
    	$model->setSourcespath("my-path");
    	$path = $model->getSourcespath();
    	$this->assertEquals("my-path", $path);
    }
    
    public function testSetGetResources () {
    	$model = new PageModel(null, null, null);
    	$model->setResources("my-url");
    	$url = $model->getResources();
    	$this->assertEquals("my-url", $url);
    }
    
    public function testSetGetResources_ext () {
    	$model = new PageModel(null, null, null);
    	$model->setResources_ext("my-url");
    	$url = $model->getResources_ext();
    	$this->assertEquals("my-url", $url);
    }
    
    public function testSetGetData () {
    	$model = new PageModel(null, null, null);
    	$model->setData("{}");
    	$data = $model->getData();
    	$this->assertEquals("{}", $data);
    }
    
    public function testSetGetMandantId () {
    	$model = new PageModel(null, null, null);
    	$model->setMandantId(1);
    	$mandantId = $model->getMandantId();
    	$this->assertEquals(1, $mandantId);
    }

}