<?php
namespace PHPagstractTest\Symbol\Symbols;

use PHPagstract\Symbol\Symbols\Tokens\Element;
use PHPagstract\Symbol\Symbols\Properties\RootProperty;
use PHPagstract\Symbol\Symbols\Properties\ScalarProperty;
use PHPagstract\Symbol\Symbols\SymbolFactory;
use PHPagstract\Page\Model\PageModel;
use PHPagstract\Page\Config\PageConfig;
use PHPagstract\Renderer\Renderer;

/**
 * PHPagstract abstract/token symbol class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class AbstractSymbolTest extends \PHPUnit_Framework_TestCase
{
    
    public function testToString() 
    {
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $testString = $symbol->toString();
        
        $this->assertEquals('', $testString);
    }
    
    public function testAbstractCompile() 
    {
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $testString = $symbol->compile();
        
        $this->assertEquals('', $testString);
    }
    
    public function testSetGetName() 
    {
        $symbol = $this
            ->getMockBuilder('PHPagstract\\Symbol\\Symbols\\AbstractSymbol')
            ->setMethods(array('setName', 'getName'))
            ->getMockForAbstractClass();
        $symbol
            ->method('getName')
            ->willReturn('my-name');
        
        $symbol->setName("my-name");
        $testName = $symbol->getName();
        
        $this->assertEquals('my-name', $testName);
    }
    
    public function testSymbolsTokenGetHasAttributes() 
    {
        $attr = array(
            "foo" => "bar"
        );
        $token = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken', 
            array("element", null, null),
            '', null, null, null, 
            array('hasAttributes', 'getAttributes')
        );
        $token->method('hasAttributes')->willReturn(true);
        $token->method('getAttributes')->willReturn($attr);
        
        $symbol = new Element();
        $symbol->setToken($token);
        
        $testAttr = $symbol->getAttributes();
        
        $this->assertEquals($attr, $testAttr);
        $this->assertTrue($symbol->hasAttributes());
        
    }

    public function testSymbolsTokenGetHasNoAttributes() 
    {
        $token = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken', 
            array("element", null, null),
            '', null, null, null, 
            array('hasAttributes', 'getAttributes')
        );
        $token->method('hasAttributes')->willReturn(false);
        $token->method('getAttributes')->willReturn(array());
        
        $symbol = new Element();
        $symbol->setToken($token);
        
        $testAttr = $symbol->getAttributes();
        
        $this->assertEquals(array(), $testAttr);
        $this->assertFalse($symbol->hasAttributes());
    }
    
    public function testSymbolsTokenGetValue() 
    {
        $token = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken', 
            array("element", null, null),
            '', null, null, null, 
            array('getValue')
        );
        $token->method('getValue')->willReturn("my value");
        
        $symbol = new Element();
        $symbol->setToken($token);
        
        $testValue = $symbol->getValue();
        
        $this->assertEquals("my value", $testValue);
    }
    
    public function testSymbolsTokenMethodsDoNotExist() 
    {
        $token = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\Text'
        );
        
        $symbol = new Element();
        $symbol->setToken($token);

        $this->assertFalse($symbol->hasAttributes());
        $this->assertEquals(array(), $symbol->getAttributes());

        $token = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken', 
            array("text", null, null),
            '', null, null, null, 
            array()
        );
        
        $symbol = new Element();
        $symbol->setToken($token);
        
        $this->assertNull($symbol->getValue());
    }
    
    public function testSetGetChildren() 
    {
        $childSymbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        
        $symbol = new Element();
        
        $symbol->setChildren(
            array(
            $childSymbol,
            $childSymbol
            )
        );
        
        $testArray = $symbol->getChildren();
        
        $this->assertTrue($symbol->hasChildren());
        $this->assertEquals(
            array( 
                $childSymbol, 
                $childSymbol
            ), 
            $testArray
        ); 
        
    }
    
    public function testToArrayAbstractSymbol() 
    {
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $testArray = $symbol->toArray();
        $this->assertEquals(
            array(
                'name' => 'Symbol',
                'line' => null,
                'position' => null,
            ),
            $testArray
        );
        
    }
        
    public function testToArrayWithAttributesAndChildren() 
    {
           $token = $this->getMockForAbstractClass(
               'PHPagstract\\Token\\Tokens\\AbstractToken',
               array("text", null, null),
               '', null, null, null,
               array('getLine', 'getPosition', 'getAttributes')
           );
        $token->method('getAttributes')->willReturn(
            array(
            'foo' => 'bar'
            )
        );
        
        $symbol = new Element();
        $symbol->setToken($token);
        
        $testArray = $symbol->toArray();
        $this->assertEquals(
            array(
                'name' => 'Symbol',
                'line' => null,
                'position' => null,
                'token' => null,
                'attributes' => array(
                    'foo' => 'bar'
                ),
            ), 
            $testArray
        );
        
        $childSymbol = new Element();
        $childSymbol->setToken($token);
        
        $testArray = $childSymbol->toArray();
        $this->assertEquals(
            array(
                'name' => 'Symbol',
                'line' => null,
                'position' => null,
                'token' => null,
                'attributes' => array(
                    'foo' => 'bar'
                ),
            ), 
            $childSymbol->toArray()
        );
        
        
        $symbol = new Element();
        $symbol->setToken($token);
        $symbol->isClosing(true);
        
        $symbol->setChildren(
            array(
            $childSymbol, $childSymbol
            )
        );
        
        $testArray = $symbol->toArray();
        $this->assertEquals(
            array(
                'name' => 'Symbol',
                'line' => null,
                'position' => null,
                'token' => null,
                'attributes' => array(
                    'foo' => 'bar'
                ),
                'closing' => true,
                'children' => array(
                    array(
                        'name' => 'Symbol',
                        'line' => null,
                        'position' => null,
                        'token' => null,
                        'attributes' => array(
                            'foo' => 'bar'
                        ),
                    ), 
                    array(
                        'name' => 'Symbol',
                        'line' => null,
                        'position' => null,    
                        'token' => null,
                        'attributes' => array(
                            'foo' => 'bar'
                        ),
                    ), 
                ),
            ), 
            $symbol->toArray()
        );
         
    }
        
    public function testPropertySymbolSetGetParents() 
    {
        $root = new RootProperty();
        
        $prop = new ScalarProperty('test', null);
        
        $testNull = $prop->getParent();
         
        $this->assertEquals($prop, $testNull);
        
        $prop->setParent($root);
        $testProp = $prop->getParent();
        
        $this->assertEquals($root, $testProp);
    }
        
    public function testAbstractSymbolsSetGetParents() 
    {
        $parent = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        
        $testNull = $symbol->getParent();
         
        $this->assertEquals($symbol, $testNull);
        
        $symbol->setParent($parent);
        $testSymbol = $symbol->getParent();
        
        $this->assertEquals($parent, $testSymbol);
    }
        
    public function testAbstractTokenSymbolInitializedWithParent() 
    {
        $parent = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol');

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array($parent, null));
        
        $testSymbol = $symbol->getParent();

        $this->assertEquals($parent, $testSymbol);
        $this->assertSame($parent, $testSymbol);
    }
    
    public function testSetGetPageModel() 
    {

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol');
        $pageModel = new PageModel();
        
        $symbol->setPageModel($pageModel);
        $testPageModel = $symbol->getPageModel();

        $this->assertNotNull($testPageModel);
        $this->assertEquals($pageModel, $testPageModel);
        $this->assertSame($pageModel, $testPageModel);
    }
    
    /**
     * @expectedException \PHPagstract\Exception
     */
    public function testGetPageModelThrowsException() 
    {

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array(null, true));
        $testPageModel = $symbol->getPageModel();
    }
    
    /**
     * @expectedException \PHPagstract\Exception
     */
    public function testGetRendererThrowsException() 
    {

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array(null, true));
        $testRenderer = $symbol->getRenderer();
    }
    
    public function testSetGetRenderer() 
    {
        
        $renderer = new Renderer();
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array(null, true));
        $symbol->setRenderer($renderer);
        $testRenderer = $symbol->getRenderer();

        $this->assertNotNull($testRenderer);
        $this->assertEquals($renderer, $testRenderer);
        $this->assertSame($renderer, $testRenderer);
    }
    
    /**
     * @expectedException \PHPagstract\Exception
     */
    public function testGetConfigurationThrowsException() 
    {

        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array(null, true));
        $testConfiguration = $symbol->getConfiguration();
    }
    
    public function testSetGetConfiguration() 
    {
        
        $configuration = new PageConfig();
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol', array(null, true));
        $symbol->setConfiguration($configuration);
        $testConfiguration = $symbol->getConfiguration();

        $this->assertNotNull($testConfiguration);
        $this->assertEquals($configuration, $testConfiguration);
        $this->assertSame($configuration, $testConfiguration);
        
        $testConfig = $symbol->config();

        $this->assertNotNull($testConfig);
        $this->assertEquals($configuration, $testConfig);
        $this->assertSame($configuration, $testConfig);
        
    }
    
}

