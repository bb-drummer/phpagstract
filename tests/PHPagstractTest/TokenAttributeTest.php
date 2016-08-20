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

use PHPagstract\Token\TokenAttribute;
use PHPagstract\Token\TokenAttributeAbstract;

class TokenAttributeTest extends TestCase
{
    public function testInstantiateObject()
    {
    	try {
    		$model = new TokenAttribute(null, null, null);
    		$className = get_class($model);
    	} catch (Exception $e) {
    		$tpl = null;
    		$className = null;
    	}

    	$this->assertNotNull($model);
    	$this->assertNotNull($className);
    	
		$mockTokenAttribute = $this->createMock("\PHPagstract\Token\TokenAttribute");
    	$this->assertInstanceOf("\PHPagstract\Token\TokenAttributeAbstract", $mockTokenAttribute);
    	$this->assertInstanceOf("\PHPagstract\Token\TokenAttribute", $mockTokenAttribute);
    }
    
    public function testSetGetName () {
    	$model = new TokenAttribute(null, null, null);
    	$model->setName("my-name");
    	$name = $model->getName();
    	$this->assertEquals("my-name", $name);
    }
    
    public function testSetGetValue () {
    	$model = new TokenAttribute(null, null, null);
    	$model->setValue("my-value");
    	$value = $model->getValue();
    	$this->assertEquals("my-value", $value);
    }
    
    public function testSetGetDefaultvalue () {
    	$model = new TokenAttribute(null, null, null);
    	$model->setDefaultvalue("my-value");
    	$value = $model->getDefaultvalue();
    	$this->assertEquals("my-value", $value);
    }
    
    public function testSetGetType () {
    	$model = new TokenAttribute(null, null, null);
    	$model->setType("my-type");
    	$type = $model->getType();
    	$this->assertEquals("my-type", $type);
    }

}