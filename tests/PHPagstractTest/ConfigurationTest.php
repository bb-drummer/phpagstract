<?php
namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\AbstractConfiguration;

/**
 * PHPagstract abstract configuration class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class ConfigurationTest extends TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $configuration = new AbstractConfiguration();
            $className = get_class($configuration);
        } catch (Exception $e) {
            $configuration = null;
            $className = null;
        }

        $this->assertNotNull($configuration);
        $this->assertNotNull($className);
        
        $this->assertInstanceOf("\PHPagstract\AbstractConfiguration", $configuration);
        $this->assertEquals("PHPagstract\AbstractConfiguration", $className);
        
    }
    
    public function testSetGet() 
    {
        
        $configuration = new AbstractConfiguration();
        $configuration->set("myvar", "my-value");
        $testValue = $configuration->get("myvar");

        $this->assertNotNull($testValue);
        $this->assertEquals("my-value", $testValue);
        
    }
    
    public function testCallGet() 
    {

        $configuration = new AbstractConfiguration();
        $configuration->set("myvar", "my-value");
        $testValue = $configuration->myvar();

        $this->assertNotNull($testValue);
        $this->assertEquals("my-value", $testValue);
        
    }
    
    public function testCallSet() 
    {

        $configuration = new AbstractConfiguration();
        $configuration->myothervar('my-other-value');
        $testValue = $configuration->myothervar();

        $this->assertNotNull($testValue);
        $this->assertEquals("my-other-value", $testValue);
        
    }
    
    public function testGetReturnsNullOnUnknownKey() 
    {

        $configuration = new AbstractConfiguration();
        $testValue = $configuration->get("myUnknownVar");

        $this->assertNull($testValue);
        $this->assertEmpty($testValue);
        
    }
    
    public function testSetConfigArray() 
    {

        $configuration = new AbstractConfiguration();
        $configuration->setConfig(array("myvar" => "my-value"));
        $testValue = $configuration->myvar();

        $this->assertNotNull($testValue);
        $this->assertEquals("my-value", $testValue);
        
    }
    
    public function testSetConfigObject() 
    {

        $config = (object)array("myvar" => "my-value");
        $configuration = new AbstractConfiguration();
        $configuration->setConfig($config);
        $testValue = $configuration->myvar();

        $this->assertNotNull($testValue);
        $this->assertEquals("my-value", $testValue);
        
    }
    
    public function testInitializeWithConfig() 
    {

        $configuration = new AbstractConfiguration(array("myvar" => "my-value"));
        $testValue = $configuration->myvar();

        $this->assertNotNull($testValue);
        $this->assertEquals("my-value", $testValue);
        
    }

}