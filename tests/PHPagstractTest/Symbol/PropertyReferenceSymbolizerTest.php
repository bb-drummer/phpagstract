<?php
namespace PHPagstractTest\Symbol;

use PHPagstract\Symbol\PropertyReferenceSymbolizer;
use PHPagstract\Symbol\Symbols\Properties\RootProperty;
use PHPagstract\Symbol\Symbols\Properties\ComponentProperty;
use PHPagstract\Symbol\Symbols\Properties\ActionProperty;
use PHPagstract\Symbol\Symbols\Properties\FormProperty;

/**
 * PHPagstract property reference resolver class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyReferenceSymbolizerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $resolver = new PropertyReferenceSymbolizer();
            $className = get_class($resolver);
        } catch (Exception $e) {
            $resolver = null;
            $className = null;
        }

        $this->assertNotNull($resolver);
        $this->assertNotNull($className);
        $this->assertInstanceOf("\PHPagstract\Symbol\PropertyReferenceSymbolizer", $resolver);
        
        $mockResolver = $this->createMock("\PHPagstract\Symbol\PropertyReferenceSymbolizer");
        $this->assertInstanceOf("\PHPagstract\Symbol\PropertyReferenceSymbolizer", $mockResolver);
        
    }
    
    public function testInstantiateComponentPropertyObject()
    {
        
        $root = new RootProperty();
        $componentProperty = new ComponentProperty('test', $root);

        $this->assertEquals("test", $componentProperty->getName());
        $this->assertEquals("component", $componentProperty->getType());
        $this->assertEquals($root, $componentProperty->getParent());
    }

    public function testInstantiateActionPropertyObject()
    {
    
        $root = new RootProperty();
        $actionProperty = new ActionProperty('test', $root);
    
        $this->assertEquals("test", $actionProperty->getName());
        $this->assertEquals("action", $actionProperty->getType());
        $this->assertEquals($root, $actionProperty->getParent());
    }

    public function testInstantiateFormPropertyObject()
    {
    
        $root = new RootProperty();
        $formProperty = new FormProperty('test', $root);
    
        $this->assertEquals("test", $formProperty->getName());
        $this->assertEquals("form", $formProperty->getType());
        $this->assertEquals($root, $formProperty->getParent());
    }
    
    

}

