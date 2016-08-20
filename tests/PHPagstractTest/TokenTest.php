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

use \PHPagstract\Token;
use \PHPagstract\TokenAbstract;

class TokenTest extends TestCase
{
    public function testInstantiateObject()
    {
    	try {
    		$token = new Token(null, null);
    		$className = get_class($token);
    	} catch (Exception $e) {
    		$token = null;
    		$className = null;
    	}

    	$this->assertNotNull($token);
    	$this->assertNotNull($className);
    	
		$mockToken = $this->createMock("\PHPagstract\Token");
    	$this->assertInstanceOf("\PHPagstract\TokenAbstract", $mockToken);
    	$this->assertInstanceOf("\PHPagstract\Token", $mockToken);
    }
    
    public function testSetGetName () {
    	$model = new Token(null, null, null);
    	$model->setName("my-name");
    	
    	$name = $model->getName();
    	$this->assertEquals("my-name", $name);
    }
    
    public function testSetGetStartTag () {
    	$model = new Token(null, null, null);
    	$model->setStartTag("my-tag");
    	
    	$tag = $model->getStartTag();
    	$this->assertEquals("my-tag", $tag);
    }
    
    public function testSetGetEndTag () {
    	$model = new Token(null, null, null);
    	$model->setEndTag("my-tag");
    	
    	$tag = $model->getEndTag();
    	$this->assertEquals("my-tag", $tag);
    }
    
    public function testSetGetNamespace () {
    	$model = new Token(null, null, null);
    	$model->setNamespace("my-namespace");
    	
    	$namespace = $model->getNamespace();
    	$this->assertEquals("my-namespace", $namespace);
    }
    
    public function testSetGetAttributes () {
    	$model = new Token(null, null, null);
    	$model->setAttributes(array());
    	
    	$attributes = $model->getAttributes();
    	$this->assertEquals(array(), $attributes);
    	$this->assertEmpty($attributes);
    }
    
    public function testGetAttribute () {
    	$model = new Token(null, null, null);
    	$model->setAttributes(array("attr" => "value"));
    	
    	$attribute = $model->getAttributes();
    	$this->assertEquals(array("attr" => "value"), $attribute);
    	
    	$attribute = $model->getAttribute("attr");
    	$this->assertNotNull($attribute);
    	$this->assertEquals("value", $attribute);
    	
    }

}