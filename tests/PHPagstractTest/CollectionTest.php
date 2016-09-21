<?php
namespace PHPagstractTest;

/**
 * PHPagstract abstract collection class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\AbstractCollection;

class CollectionTest extends TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $collection = new AbstractCollection();
            $className = get_class($collection);
        } catch (Exception $e) {
            $collection = null;
            $className = null;
        }

        $this->assertNotNull($collection);
        $this->assertNotNull($className);
        
        $this->assertInstanceOf("\PHPagstract\AbstractCollection", $collection);
        $this->assertEquals("PHPagstract\AbstractCollection", $className);
        
    }
    
    public function testSetGetType() 
    {
        
        $collection = new AbstractCollection();
        $collection->setType("my-type");
        $testType = $collection->getType();
        
        $this->assertEquals("my-type", $testType);
        
    }
    
    public function testGetSetOnlyValidType() 
    {

    	$collection = new AbstractCollection();
    	
    	$collection->onlyValidType(true);
    	$testFlag = $collection->onlyValidType();
    	$this->assertTrue($testFlag);
    	
    	$collection->onlyValidType(false);
    	$testFlag = $collection->onlyValidType();
    	$this->assertFalse($testFlag);
        
    }

}