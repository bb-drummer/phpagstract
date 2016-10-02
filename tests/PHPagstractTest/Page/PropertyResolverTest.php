<?php
namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\Symbol\PropertyReferenceSymbolizer;
use PHPagstract\Symbol\Symbols\Properties\RootProperty;
use PHPagstract\Page\Resolver\PropertyResolver;


/**
 * PHPagstract filepath resolver class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

class PropertyResolverTest extends TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $resolver = new PropertyResolver();
            $className = get_class($resolver);
        } catch (Exception $e) {
            $resolver = null;
            $className = null;
        }

        $this->assertNotNull($resolver);
        $this->assertNotNull($className);
        $this->assertInstanceOf("\\PHPagstract\\Page\\Resolver\\PropertyResolver", $resolver);
        
        $mockResolver = $this->createMock("\\PHPagstract\\Page\\Resolver\\PropertyResolver");
        $this->assertInstanceOf("\\PHPagstract\\Page\\Resolver\\PropertyResolver", $mockResolver);
        
    }
    
    public function testInstantiateObjectWithDataParameter()
    {
        
        try {
            $data = (object)[ "root" => (object)[] ];
            $symbolizer = new PropertyReferenceSymbolizer();
            $properties = $symbolizer->symbolize($data);
            $resolver = new PropertyResolver($properties);
            $className = get_class($resolver);
        } catch (Exception $e) {
            $resolver = null;
            $className = null;
        }

        $this->assertNotNull($resolver);
        $this->assertNotNull($className);
        $this->assertInstanceOf("\\PHPagstract\\Page\\Resolver\\PropertyResolver", $resolver);
        
        $scopeProperty = $resolver->getRootScope();
        $this->assertEquals('PHPagstract\\Symbol\\Symbols\\Properties\\RootProperty', get_class($scopeProperty));
        $this->assertEquals('root', ($scopeProperty->getName()));
        $this->assertEquals('root', ($scopeProperty->getType()));
        $this->assertNull(($scopeProperty->getProperty()));
        
    }
    
    /**
     * @dataProvider simpleGettersSettersDataProvider
     */
    public function testSimpleGettersSetters($varname, $value) 
    {
        $resolver = new PropertyResolver();

        $setFunc = 'set'.ucfirst($varname);
        $getFunc = 'get'.ucfirst($varname);
        
        $resolver->$setFunc($value);
        $test = $resolver->$getFunc($value);
        $this->assertEquals($value, $test);
    }

    public function simpleGettersSettersDataProvider() 
    {
        return [
            'setGetScopes' => [
                "scopes", // var/method name
                [ (object)[ "root" => (object)[] ] ]          // value
            ],
        ];
    }    
    
    /**
     * @dataProvider parsePropertyReferenceStringsDataProvider
     */
    public function testParsePropertyReferenceStrings($reference, array $expectedArray, $debug = false)
    {

        $resolver = new PropertyResolver();
        
        $tokens = $resolver->parsePropertyReferenceString($reference);
        if ($debug) {
            var_export($reference); var_export($expectedArray); var_export($tokens);
        }

        $this->assertSame(
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
                    )    ,
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
        
        $resolver = new PropertyReferenceSymbolizer();
        $context = json_decode($jsondata);
        $rootSymbolTree = $resolver->symbolize($context);

        $resolver = new PropertyResolver();
        $resolver->setStream($rootSymbolTree);
        $resolver->resetScopes();
        $resolver->addScope($rootSymbolTree);
        $rootScope = $resolver->getRootScope();
        $resolver->setContext($rootScope);
        
        $property = $resolver->getPropertyByReference($expectedName);
        
        if ($debug) {
            echo "data: "; var_export($jsondata); echo PHP_EOL;
            echo "class: "; var_export($expectedClassname); echo PHP_EOL;
            echo "name: "; var_export($expectedName); echo PHP_EOL;
            echo "type: "; var_export($expectedType); echo PHP_EOL;
            echo "exp: "; var_export($expectedValue); echo PHP_EOL;
            echo "val: "; var_export($property->serialize()); echo PHP_EOL;
        }

        $this->assertEquals($expectedClassname, get_class($property));
        $this->assertEquals($expectedName, ($property->getName()));
        $this->assertEquals($expectedType, ($property->getType()));
        $this->assertEquals($expectedValue, ($property->serialize()));
        $this->assertSameSize($expectedValue, ($property->serialize()));
        
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
                  'property' => null,
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
                  'property' => null,
                  'items' => 
                  array (
                    0 => 
                    array (
                      'name' => 0,
                      'type' => 'object',
                      'property' => null,
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
                      'property' => null,
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
        
        $resolver = $this->getPropertyResolver();
        
        $intermediateContext = $resolver->getPropertyByReference('property');

        $resolver->setContext($intermediateContext);

        $property = $resolver->getPropertyByReference('../myproperty');
        
        $this->assertEquals('PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', get_class($property));
        $this->assertEquals('myproperty', ($property->getName()));
        $this->assertEquals('scalar', ($property->getType()));
        $this->assertEquals('myvalue', ($property->getProperty()));
        $this->assertSame(
            array (
                'name' => 'myproperty',
                'type' => 'scalar',
                'property' => 'myvalue',
            ), ($property->serialize()) 
        );
        


        $property = $resolver->getPropertyByReference('../child');
        $this->assertEquals(null, ($property));
        
    }
    
    
    public function testLookupListItemsPropertyAndSerialize()
    {
        
        $resolver = $this->getPropertyResolver();
        
        $property = $resolver->getPropertyByReference('otherproperty[0]');

        $this->assertEquals('PHPagstract\\Symbol\\Symbols\\Properties\\ObjectProperty', get_class($property));
        $this->assertEquals('0', ($property->getName()));
        $this->assertEquals('object', ($property->getType()));
        $this->assertEquals(null, ($property->getProperty()));
        
        $property = $resolver->getPropertyByReference('otherproperty[1].child.other[0]');

        //echo PHP_EOL . var_export($property->serialize(), true) . PHP_EOL;
        $this->assertEquals('PHPagstract\\Symbol\\Symbols\\Properties\\ObjectProperty', get_class($property));
        $this->assertEquals('0', ($property->getName()));
        $this->assertEquals('object', ($property->getType()));
        $this->assertEquals(null, ($property->getProperty()));
        
    }
    
    
    public function testLookupListParentPropertyAndSerialize()
    {
        
        $resolver = $this->getPropertyResolver();
        
        $intermediateContext = $resolver->getPropertyByReference('otherproperty[1].child');
        
        $resolver->addScope($intermediateContext);

        $property = $resolver->getPropertyByReference('../hello');
        $this->assertEquals('PHPagstract\\Symbol\\Symbols\\Properties\\ScalarProperty', get_class($property));
        $this->assertEquals('hello', ($property->getName()));
        $this->assertEquals('scalar', ($property->getType()));
        $this->assertEquals('world', ($property->getProperty()));
        $this->assertSame(
            array (
                'name' => 'hello',
                'type' => 'scalar',
                'property' => 'world',
            ), ($property->serialize()) 
        );
        
        // clean up scopes
        $resolver->unsetLastScope();
        
    }
    
    /**
     * @dataProvider getPropertyByReferencesDataProvider
     */
    public function testGetPropertyByReferences($filename, $reference, $expectedData, $debug = false)
    {
        $resolver = new PropertyReferenceSymbolizer();
        $jsondata = (file_get_contents($filename));
        
        if (is_string($expectedData) && file_exists($expectedData)) {
            $expectedData = include $expectedData;
        }
        $context = json_decode($jsondata);
        $rootSymbolTree = $resolver->symbolize($context);

        $resolver = new PropertyResolver();
        $resolver->setStream($rootSymbolTree);
        $resolver->resetScopes();
        $resolver->addScope($rootSymbolTree);
        $rootScope = $resolver->getRootScope();
        $resolver->setContext($rootScope);

        $property = $resolver->getPropertyByReference($reference);
        
        if ($debug) {
            echo "data: "; var_export($jsondata); echo PHP_EOL;
            echo "file: "; var_export($filename); echo PHP_EOL;
            echo "to parse: "; var_export($reference); echo PHP_EOL;
            echo "expected: "; var_export($expectedData); echo PHP_EOL;
            //echo "serialized: "; var_export($property); echo PHP_EOL;
            echo "serialized: "; var_export($property->serialize()); echo PHP_EOL;
        }
        
        $this->assertInstanceOf('PHPagstract\\Symbol\\Symbols\\AbstractPropertySymbol', $property);
        $this->assertNotNull($expectedData, $property);
        //$this->assertSame( $expectedData,  ($property->serialize()) );
        $this->assertEquals($expectedData, ($property->serialize()));
        $this->assertSameSize($expectedData, ($property->serialize()));
    }
    
    /**
     * @dataProvider getPropertyByReferencesDataProvider
     */
    public function testGetValueByReferences($filename, $reference, $expectedData, $debug = false)
    {
        $resolver = new PropertyReferenceSymbolizer();
        $jsondata = (file_get_contents($filename));
        
        if (is_string($expectedData) && file_exists($expectedData)) {
            $expectedData = include $expectedData;
        }
        $context = json_decode($jsondata);
        $rootSymbolTree = $resolver->symbolize($context);

        $resolver = new PropertyResolver();
        $resolver->setStream($rootSymbolTree);
        $resolver->resetScopes();
        $resolver->addScope($rootSymbolTree);
        $rootScope = $resolver->getRootScope();
        $resolver->setContext($rootScope);

        $propertyValue = $resolver->getValueByReference($reference);

        if ($debug) {
            echo "data: "; var_export($jsondata); echo PHP_EOL;
            echo "file: "; var_export($filename); echo PHP_EOL;
            echo "to parse: "; var_export($reference); echo PHP_EOL;
            echo "expected: "; var_export($expectedData); echo PHP_EOL;
            if (is_object($propertyValue) && method_exists($propertyValue, "serialize") ) { 
                echo "serialized: "; var_export($propertyValue->serialize()); echo PHP_EOL; 
            } else {
                echo "serialized: "; var_export($propertyValue); echo PHP_EOL; 
            }
        }
        
        if ($expectedData['type'] == 'list') {
            $this->assertCount(count($expectedData['items']), $propertyValue);
        } else if ($expectedData['type'] == 'object') {
            foreach ($expectedData['properties'] as $idx => $expectedItem) {
                $this->assertEquals(($expectedItem['name']), $propertyValue->$idx->getName());
                $this->assertEquals(($expectedItem['type']), $propertyValue->$idx->getType());
            }
        } else {
            $this->assertEquals($expectedData['property'], $propertyValue);
        }
    }
    
    public function getPropertyByReferencesDataProvider()
    {
        return array(
                
            "string value" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "stringValue",
                array (
                  'name' => 'stringValue',
                  'type' => 'scalar',
                  'property' => 'this is a text...',
                ),
                $debug = false
            ),
                
            "integer value" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "integerValue",
                array (
                  'name' => 'integerValue',
                  'type' => 'scalar',
                  'property' => 123,
                ),
                $debug = false
            ),
                
            "float value" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "floatValue",
                array (
                  'name' => 'floatValue',
                  'type' => 'scalar',
                  'property' => 1.23,
                ),
                $debug = false
            ),
                
            "boolean value (true)" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "trueValue",
                array (
                  'name' => 'trueValue',
                  'type' => 'scalar',
                  'property' => true,
                ),
                $debug = false
            ),
                
            "boolean value (false)" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "falseValue",
                array (
                  'name' => 'falseValue',
                  'type' => 'scalar',
                  'property' => false,
                ),
                $debug = false
            ),
                
            "null value" => array(
                __DIR__."/../Symbol/Json/json-values.json",
                "nullValue",
                array (
                  'name' => 'nullValue',
                  'type' => 'scalar',
                  'property' => null,
                ),
                $debug = false
            ),
                
            "list of scalars" => array(
                __DIR__."/../Symbol/Json/json-listOfValues.json",
                "scalarList",
                array (
                  'name' => 'scalarList',
                  'type' => 'list',
                  'property' => null,
                  'items' =>
                        array (
                                0 =>
                                array (
                                        'name' => 0,
                                        'type' => 'scalar',
                                        'property' => 'this is a text...',
                                ),
                                1 =>
                                array (
                                        'name' => 1,
                                        'type' => 'scalar',
                                        'property' => 'this is some other text...',
                                ),
                                2 =>
                                array (
                                        'name' => 2,
                                        'type' => 'scalar',
                                        'property' => 'this is another text...',
                                ),
                                3 =>
                                array (
                                        'name' => 3,
                                        'type' => 'scalar',
                                        'property' => 'this is more text...',
                                ),
                                4 =>
                                array (
                                        'name' => 4,
                                        'type' => 'scalar',
                                        'property' => 'this is even more text...',
                                ),
                                5 =>
                                array (
                                        'name' => 5,
                                        'type' => 'scalar',
                                        'property' => 123,
                                ),
                                6 =>
                                array (
                                        'name' => 6,
                                        'type' => 'scalar',
                                        'property' => 123.45,
                                ),
                                7 =>
                                array (
                                        'name' => 7,
                                        'type' => 'scalar',
                                        'property' => null,
                                ),
                                8 =>
                                array (
                                        'name' => 8,
                                        'type' => 'scalar',
                                        'property' => false,
                                ),
                                9 =>
                                array (
                                        'name' => 9,
                                        'type' => 'scalar',
                                        'property' => true,
                                ),
                        ),
                ),
                $debug = false
            ),
                
            "first of list of scalars" => array(
                __DIR__."/../Symbol/Json/json-listOfValues.json",
                "scalarList[0]",
                array (
                  'name' => 0,
                  'type' => 'scalar',
                  'property' => 'this is a text...',
                ),
                $debug = false
            ),
                
            "second of list of scalars" => array(
                __DIR__."/../Symbol/Json/json-listOfValues.json",
                "scalarList[1]",
                array (
                  'name' => 1,
                  'type' => 'scalar',
                  'property' => 'this is some other text...',
                ),
                $debug = false
            ),
                
            "objects" => array(
                __DIR__."/../Symbol/Json/json-objects.json",
                "object1",
                array (
                  'name' => 'object1',
                  'type' => 'object',
                  'property' => null,
                  'properties' => 
                  (object)(array(
                     'text1' => 
                    array (
                      'name' => 'text1',
                      'type' => 'scalar',
                      'property' => 'this is a text...',
                    ),
                     'text2' => 
                    array (
                      'name' => 'text2',
                      'type' => 'scalar',
                      'property' => 'this is another text...',
                    ),
                  )),
                ),
                $debug = false
            ),
            
            "list of objects" => array(
                __DIR__."/../Symbol/Json/json-listOfObjects.json",
                "objectList",
                array (
                  'name' => 'objectList',
                  'type' => 'list',
                  'property' => null,
                  'items' => 
                  array (
                    0 => 
                    array (
                      'name' => 0,
                      'type' => 'object',
                      'property' => null,
                      'properties' => 
                      (object)(array(
                         'text' => 
                        array (
                          'name' => 'text',
                          'type' => 'scalar',
                          'property' => 'this is a text...',
                        ),
                      )),
                    ),
                    1 => 
                    array (
                      'name' => 1,
                      'type' => 'object',
                      'property' => null,
                      'properties' => 
                      (object)(array(
                         'text' => 
                        array (
                          'name' => 'text',
                          'type' => 'scalar',
                          'property' => 'this is some other text...',
                        ),
                      )),
                    ),
                    2 => 
                    array (
                      'name' => 2,
                      'type' => 'object',
                      'property' => null,
                      'properties' => 
                      (object)(array(
                         'text' => 
                        array (
                          'name' => 'text',
                          'type' => 'scalar',
                          'property' => 'this is another text...',
                        ),
                      )),
                    ),
                    3 => 
                    array (
                      'name' => 3,
                      'type' => 'object',
                      'property' => null,
                      'properties' => 
                      (object)(array(
                         'text' => 
                        array (
                          'name' => 'text',
                          'type' => 'scalar',
                          'property' => 'this is more text...',
                        ),
                      )),
                    ),
                    4 => 
                    array (
                      'name' => 4,
                      'type' => 'object',
                      'property' => null,
                      'properties' => 
                      (object)(array(
                         'text' => 
                        array (
                          'name' => 'text',
                          'type' => 'scalar',
                          'property' => 'this is even more text...',
                        ),
                      )),
                    ),
                  ),
                ),
                $debug = false
            ),
            
            "object in list of objects" => array(
                __DIR__."/../Symbol/Json/json-listOfObjects.json",
                "objectList[1]",
                array (
                  'name' => 1,
                  'type' => 'object',
                  'property' => null,
                  'properties' => 
                  (object)(array(
                     'text' => 
                    array (
                      'name' => 'text',
                      'type' => 'scalar',
                      'property' => 'this is some other text...',
                    ),
                  )),
                ),
                $debug = false
            ),
            
            /* "object in list of objects" => array(
                __DIR__."/Json/json-listOfObjects.json",
                "objectList[1]",
                array (
                    'name' => 1,
                    'type' => 'object',
                    'property' => 'this is some other text...',
                ),
                $debug = true
            ), */
                
        );
    }
    
    
    public function testScopesGetRootScopeReturnsNull()
    {
        $resolver = new PropertyResolver();
        
        $rootScope = $resolver->getRootScope();
        $this->assertNull($rootScope);
    }
    
    
    
    //
    // test helpers
    //
    
    /**
     * retrieve a pre-configured ProperyResolver instance
     * 
     * @return PropertyResolver
     */
    private function getPropertyResolver( ) 
    {

        $symbolizer = new PropertyReferenceSymbolizer();
        $jsondata = (file_get_contents(__DIR__."/../Symbol/Json/json-parse-test.json"));
        $context = json_decode($jsondata);
        $rootSymbolTree = $symbolizer->symbolize($context);

        $resolver = new PropertyResolver();
        $resolver->setStream($rootSymbolTree);
        $resolver->resetScopes();
        $resolver->addScope($rootSymbolTree);
        $rootScope = $resolver->getRootScope();
        $resolver->setContext($rootScope);
        
        return $resolver;
    }

}