<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\MessageTokenizer;

/**
 * PHPagstract messages tokenizer class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class MessageTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParseHTMLOnly($html, array $expectedTokenArray, $debug = false)
    {
        
        $htmlTokenizer = new MessageTokenizer();
        $tokens = $htmlTokenizer->parse($html);
        if ($debug) {
            var_export($html); var_export($expectedTokenArray); var_export($tokens->toArray());
        }

        $this->assertEquals(
            $expectedTokenArray,
            $tokens->toArray()
        );
        
    }

    
    public function parseDataProvider()
    {
        return array(
            'text only' => array(
                'this is text only ',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'this is text only',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'single empty reference' => array(
                'msg://',
                array(
                    array (
                        'type' => 'PagstractMessage',
                        'name' => 'msg',
                        'value' => '',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
            ),
            'a single msg reference' => array(
                'msg://some.text-to_substiute',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => 'some.text-to_substiute',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single empty msg reference inside text' => array(
                'and there, some msg:// will be substituted      ',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'and there, some ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => '',
                    'line' => 0,
                    'position' => 16,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' will be substituted',
                    'line' => 0,
                    'position' => 22,
                  ),
                )
            ),
            'a single msg reference inside text' => array(
                'some text with a msg://some.text-to_substitute reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some text with a ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => 'some.text-to_substitute',
                    'line' => 0,
                    'position' => 17,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' reference ...',
                    'line' => 0,
                    'position' => 46,
                  ),
                )
            ),
            'a single msg reference inside text with single backslash' => array(
                'some text with a msg:/// reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some text with a ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => '',
                    'line' => 0,
                    'position' => 17,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => '/ reference ...',
                    'line' => 0,
                    'position' => 23,
                  ),
                )
            ),
                
            'a single msg reference inside tag'  => array(
                'some <tag with="msg://some.text-to_substitute"> here...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some <tag with="',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => 'some.text-to_substitute',
                    'line' => 0,
                    'position' => 16,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => '"> here...',
                    'line' => 0,
                    'position' => 45,
                  ),
                )
            ),

            'multiple msg references inside text' => array(
                'some msg://some.text-to_substitute will be inserted there and msg://some.other.text-to_substitute here ...',
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'value' => 'some ',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => 'some.text-to_substitute',
                    'line' => 0,
                    'position' => 5,
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'value' => ' will be inserted there and ',
                    'line' => 0,
                    'position' => 34,
                  ),
                  3 => 
                  array (
                    'type' => 'PagstractMessage',
                    'name' => 'msg',
                    'value' => 'some.other.text-to_substitute',
                    'line' => 0,
                    'position' => 62,
                  ),
                  4 => 
                  array (
                    'type' => 'text',
                    'value' => ' here ...',
                    'line' => 0,
                    'position' => 97,
                  ),
                )
            ),

        );
    }
    
}
