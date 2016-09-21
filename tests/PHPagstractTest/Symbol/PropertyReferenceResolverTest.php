<?php
namespace PHPagstractTest\Symbol;

use PHPagstract\Symbol\SymbolResolver;
use PHPagstract\Token\Tokens\TokenCollection;
use PHPagstract\Symbol\PropertyReferenceResolver;

/**
 * PHPagstract property reference resolver class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyReferenceResolverTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @expectedException PHPagstract\Symbol\Exception\SymbolException
     */
    public function testResolveThrowsException() {
        
        $resolver = new SymbolResolver(true);
        $mockToken = $this->createMock('PHPagstract\\Token\\Tokens\\AbstractToken');
        $mockToken
            ->method('getType')
            ->willReturn('blah');
        $tokens = new TokenCollection();
        $tokens[] = $mockToken; 
        $symbols = $resolver->resolve($tokens);
    }
    
    
    /**
     * @dataProvider parsePropertyReferenceStringsDataProvider
     */
    public function testParsePropertyReferenceStrings($reference, array $expectedArray, $debug = false)
    {
        
        $resolver = new PropertyReferenceResolver();
        $tokens = $resolver->parsePropertyReferenceString($reference);
        if ($debug) {
            var_export($reference); var_export($expectedArray); var_export($tokens);
        }

        $this->assertEquals(
            $expectedArray,
            $tokens
        );
        
    }

    
    public function parsePropertyReferenceStringsDataProvider()
    {
        return array(
            'no property to find' => array(
                '',
                array (
                )
            ),
            'single property name with error' => array(
                'myp!§ro#perty',
                array (
                )
            ),
            'single property name' => array(
                'myproperty',
                array (
	                array (
					    '',
					    'myproperty',
					    '',
					)	
                )
            ),
            'single property name with leading dot' => array(
                '.myproperty',
                array (
	                array (
					    '.',
					    'myproperty',
					    '',
					)	
                )
            ),
            'parent property name' => array(
                '../.myproperty',
                array (
	                array (
					    '../.',
					    'myproperty',
					    '',
					)	
                )
            ),
            'multiple chained property names' => array(
                'property.child',
                array (
	                array (
					    '',
					    'property',
					    '',
					),
	                array (
					    '.',
					    'child',
					    '',
					)	
                )
            ),
            'multiple chained property names with leading dot' => array(
                '.property.child',
                array (
	                array (
					    '.',
					    'property',
					    '',
					),
	                array (
					    '.',
					    'child',
					    '',
					)	
                )
            ),
            'multiple chained property names with list index' => array(
                '.otherproperty[0].child',
                array (
	                array (
					    '.',
					    'otherproperty',
					    '0',
					),
	                array (
					    '.',
					    'child',
					    '',
					)	
                )
            ),
            'multiple chained property names with another list index at the end' => array(
                '.otherproperty[0].child.other[1]',
                array (
	                array (
					    '.',
					    'otherproperty',
					    '0',
					),
	                array (
					    '.',
					    'child',
					    '',
					)	,
	                array (
					    '.',
					    'other',
					    '1',
					)	
                )
            ),
        );
    }
    
    
    /**
     * @dataProvider lookupPropertyAndSerializeDataProvider
     */
    public function testLookupPropertyAndSerialize($jsondata, $expectedClassname, $expectedName, $expectedType, $expectedValue, $debug = false)
    {
        
        $resolver = new PropertyReferenceResolver();
        $context = json_decode($jsondata);
		$rootSymbolTree = $resolver->symbolize($context);
		$resolver->setContext($rootSymbolTree);
        
        $property = $resolver->getPropertyByReference($expectedName);
        
        if ($debug) {
            echo "data: "; var_export($jsondata); echo PHP_EOL;
            echo "class: "; var_export($expectedClassname); echo PHP_EOL;
            echo "name: "; var_export($expectedName); echo PHP_EOL;
            echo "type: "; var_export($expectedType); echo PHP_EOL;
            echo "exp: "; var_export($expectedValue); echo PHP_EOL;
            echo "val: "; var_export($property->serialize()); echo PHP_EOL;
        }

        $this->assertEquals( $expectedClassname,  get_class($property) );
        $this->assertEquals( $expectedName,  ($property->getName()) );
        $this->assertEquals( $expectedType,  ($property->getType()) );
        $this->assertEquals( $expectedValue,  ($property->serialize()) );
        
    }

    
    public function lookupPropertyAndSerializeDataProvider()
    {
        return array(
        		
        	"scalar property (string)" => array(
        		'{"property": "value"}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => "value",
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (zero)" => array(
        		'{"property": 0}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => 0,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (integer)" => array(
        		'{"property": 123}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => 123,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (negative integer)" => array(
        		'{"property": -123}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => -123,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (float)" => array(
        		'{"property": 123.45}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => 123.45,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (negative float)" => array(
        		'{"property": -123.45}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => -123.45,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (boolean false)" => array(
        		'{"property": false}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => false,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (boolean true)" => array(
        		'{"property": true}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => true,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"scalar property (null)" => array(
        		'{"property": null}', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', // expected classname
        		'property', // expected name 
        		'scalar', // expected type 
        		array
				(
				    "name" => "property",
				    "type" => "scalar",
				    "property" => null,
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"object property" => array(
        		'{"property": { "other": "value"} }', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ObjectProperty', // expected classname
        		'property', // expected name 
        		'object', // expected type 
        		array (
				  'name' => 'property',
				  'type' => 'object',
				  'property' => NULL,
				  'properties' => 
				  (object)(array(
				     'other' => 
				    array (
				      'name' => 'other',
				      'type' => 'scalar',
				      'property' => 'value',
				    ),
				  )),
				), // expected value 
        		
        		false // true = debug on
        	),
        		
        	"list property" => array(
        		'{"property": [ { "other-A": "val1"}, { "other-B": "val2"} ] }', // json
        		'PHPagstract\\Symbol\\Symbols\\Properties\\ListProperty', // expected classname
        		'property', // expected name 
        		'list', // expected type 
        		array (
				  'name' => 'property',
				  'type' => 'list',
				  'property' => NULL,
				  'items' => 
				  array (
				    0 => 
				    array (
				      'name' => 0,
				      'type' => 'object',
				      'property' => NULL,
				      'properties' => 
				      (object)(array(
				         'other-A' => 
				        array (
				          'name' => 'other-A',
				          'type' => 'scalar',
				          'property' => 'val1',
				        ),
				      )),
				    ),
				    1 => 
				    array (
				      'name' => 1,
				      'type' => 'object',
				      'property' => NULL,
				      'properties' => 
				      (object)(array(
				         'other-B' => 
				        array (
				          'name' => 'other-B',
				          'type' => 'scalar',
				          'property' => 'val2',
				        ),
				      )),
				    ),
				  ),
				), // expected value 
        		
        		false // true = debug on
        	),
        );
    }
    
    
    public function testLookupParentPropertyAndSerialize()
    {
        $resolver = new PropertyReferenceResolver();
        $jsondata = (file_get_contents(__DIR__."/../../../data/parse-json-test01.json"));
        $context = json_decode($jsondata);
		$rootSymbolTree = $resolver->symbolize($context);
		$resolver->setContext($rootSymbolTree);
        
        $intermediateContext = $resolver->getPropertyByReference('property');

        $resolver->setContext($intermediateContext);

        $property = $resolver->getPropertyByReference('../myproperty');
        
        $this->assertEquals( 'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty',  get_class($property) );
        $this->assertEquals( 'myproperty',  ($property->getName()) );
        $this->assertEquals( 'scalar',  ($property->getType()) );
        $this->assertEquals( 'myvalue',  ($property->getProperty()) );
        $this->assertEquals( array (
        		'name' => 'myproperty',
        		'type' => 'scalar',
        		'property' => 'myvalue',
        ),  ($property->serialize()) );
        


        $property = $resolver->getPropertyByReference('../child');
        $this->assertEquals( null,  ($property) );
        
    }
    
    
    public function testLookupListItemsPropertyAndSerialize()
    {
        $resolver = new PropertyReferenceResolver();
        $jsondata = (file_get_contents(__DIR__."/../../../data/parse-json-test01.json"));
        $context = json_decode($jsondata);
		$rootSymbolTree = $resolver->symbolize($context);
		$resolver->setContext($rootSymbolTree);
        
        $property = $resolver->getPropertyByReference('otherproperty[0]');

        $this->assertEquals( 'PHPagstract\\Symbol\\Symbols\\Properties\\ObjectProperty',  get_class($property) );
        $this->assertEquals( '0',  ($property->getName()) );
        $this->assertEquals( 'object',  ($property->getType()) );
        $this->assertEquals( null,  ($property->getProperty()) );
        
        $property = $resolver->getPropertyByReference('otherproperty[1].child.other[0]');

        echo PHP_EOL . var_export($property->serialize(), true) . PHP_EOL;
        $this->assertEquals( 'PHPagstract\\Symbol\\Symbols\\Properties\\ObjectProperty',  get_class($property) );
        $this->assertEquals( '0',  ($property->getName()) );
        $this->assertEquals( 'object',  ($property->getType()) );
        $this->assertEquals( null,  ($property->getProperty()) );
        /*$this->assertEquals( array (
        		'name' => 'hello',
        		'type' => 'scalar',
        		'property' => 'world',
        ),  ($property->serialize()) );*/
        
    }
    
    
    public function testLookupListParentPropertyAndSerialize()
    {
        $resolver = new PropertyReferenceResolver();
        $jsondata = (file_get_contents(__DIR__."/../../../data/parse-json-test01.json"));
        $context = json_decode($jsondata);
		$rootSymbolTree = $resolver->symbolize($context);
		$resolver->setContext($rootSymbolTree);
        
        $intermediateContext = $resolver->getPropertyByReference('otherproperty[1].child');

        $resolver->setContext($intermediateContext);

        $property = $resolver->getPropertyByReference('../hello');
        $this->assertEquals( 'PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty',  get_class($property) );
        $this->assertEquals( 'hello',  ($property->getName()) );
        $this->assertEquals( 'scalar',  ($property->getType()) );
        $this->assertEquals( 'world',  ($property->getProperty()) );
        $this->assertEquals( array (
        		'name' => 'hello',
        		'type' => 'scalar',
        		'property' => 'world',
        ),  ($property->serialize()) );
        
    }

}

