<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\PropertyReferenceTokenizer;

class PropertyReferenceTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParseHTMLOnly($html, array $expectedTokenArray, $debug = false)
    {
        
        $htmlTokenizer = new PropertyReferenceTokenizer();
        $tokens = $htmlTokenizer->parse($html);
        if ($debug) {
            var_export($html); var_export($expectedTokenArray); var_export($tokens->toArray());
        }

        $this->assertEquals(
            $expectedTokenArray,
            $tokens->toArray()
        );
        
        // clean-up: re-init default tokenizer setup
        //new MarkupTokenizer();
    }

    public function parseDataProvider()
    {
        return array(
            'text only' => array(
                'this is text only ',
                array(
                    array(
                        'type' => 'text',
                        'value' => 'this is text only',
                        'line' => 0,
                        'position' => 0
                    )
                )
            ),
            'token matching error' => array(
                'some text with a ${.broken property reference ...',
                array(
                    array(
                        'type' => 'text',
                        'value' => 'some text with a ',
                        'line' => 0,
                        'position' => 0
                    ),
                    array(
                        'type' => 'PagstractPropertyReference',
                        'name' => null,
                        'value' => null,
                        'line' => 0,
                        'position' => 17
                    )
                )
            ),
            'a single property' => array(
                '${.a.single.property.only}',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.a.single.property.only',
                    'value' => '.a.single.property.only',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'property in text' => array(
                'some text will be ${.inserted} here...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some text will be ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.inserted',
                    'value' => '.inserted',
                    'line' => 0,
                    'position' => 18,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' here...',
                    'line' => 0,
                    'position' => 30,
                  ),
                )
            ),
            'property in text' => array(
                'and there, some text will be ${.inserted}      ',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'and there, some text will be ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.inserted',
                    'value' => '.inserted',
                    'line' => 0,
                    'position' => 29,
                  ),
                )
            ),
            'multiple properties in text' => array(
                'some text will be ${.inserted} here and ${.there} another one...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some text will be ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.inserted',
                    'value' => '.inserted',
                    'line' => 0,
                    'position' => 18,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' here and ',
                    'line' => 0,
                    'position' => 30,
                  ),
                  3 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.there',
                    'value' => '.there',
                    'line' => 0,
                    'position' => 40,
                  ),
                  4 => 
                  array (
                    'type' => 'text',
                    'value' => ' another one...',
                    'line' => 0,
                    'position' => 49,
                  ),
                )
            ),
            'property in text with token -like- delimiters' => array(
                'some text will be ${.inserted} there and but not {here} ...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some text will be ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractPropertyReference',
                    'name' => '.inserted',
                    'value' => '.inserted',
                    'line' => 0,
                    'position' => 18,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' there and but not {here} ...',
                    'line' => 0,
                    'position' => 30,
                  ),
                )
            )
        );
    }
    
}
