<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\ResourceTokenizer;

/**
 * PHPagstract resource(_ext) tokenizer class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class ResourceTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParseHTMLOnly($html, array $expectedTokenArray, $debug = false)
    {
        
        $htmlTokenizer = new ResourceTokenizer();
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
	                array (
					    'type' => 'text',
					    'value' => 'this is text only',
					    'line' => 0,
					    'position' => 0,
					)	
                )
            ),
            'single empty reference' => array(
                'resource://',
                array(
                    array (
                        'type' => 'PagstractResource',
                        'name' => 'resource',
                        'value' => '',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
            ),
            'single empty ext reference' => array(
                'resource_ext://',
                array(
                    array (
                        'type' => 'PagstractResource',
                        'name' => 'resource_ext',
                        'value' => '',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
            ),
            'a single resource reference' => array(
                'resource://path/to/sth/only',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sth/only',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource_ext reference' => array(
                'resource_ext://path/to/sth/only',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sth/only',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource reference inside text' => array(
                'some text with a resource://path/to/some.file reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource',
				    'value' => 'path/to/some.file',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 45,
				  ),
				)
            ),
            'a single resource_ext reference inside text' => array(
                'some text with a resource_ext://path/to/some.file reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/some.file',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 49,
				  ),
				)
            ),
            'a single resource reference inside text with just a backslash'  => array(
                'some text with a resource:/// reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource',
				    'value' => '/',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 29,
				  ),
				)
            ),
            'a single resource_ext reference inside text with just a backslash' => array(
                'some text with a resource_ext:/// reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => '/',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 33,
				  ),
				)
            ),
            'a single resource reference inside text ending with backslash' => array(
                'some text with a resource://path/to/sth/ reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource',
				    'value' => 'path/to/sth/',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 40,
				  ),
				)
            ),
            'a single resource_ext reference inside text ending with backslash' => array(
                'some text with a resource_ext://path/to/sth/ reference ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sth/',
				    'line' => 0,
				    'position' => 17,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' reference ...',
				    'line' => 0,
				    'position' => 44,
				  ),
				)
            ),

            'a single resource reference inside a tag' => array(
                  'some <tag with="resource://in/a/tag.jpg"> here...',
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
					    'type' => 'PagstractResource',
					    'name' => 'resource',
					    'value' => 'in/a/tag.jpg',
					    'line' => 0,
					    'position' => 16,
					  ),
					  2 => 
					  array (
					    'type' => 'text',
					    'value' => '"> here...',
					    'line' => 0,
					    'position' => 39,
					  ),
					)
            ),
           'a single resource_ext reference inside a tag' => array(
                'some <tag with="resource_ext://in/a/tag.jpg"> here...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'in/a/tag.jpg',
				    'line' => 0,
				    'position' => 16,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => '"> here...',
				    'line' => 0,
				    'position' => 43,
				  ),
				)
             ),

            'a single empty resource reference inside text' => array(
                'and there, some resource:// will be substituted    ',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource',
				    'value' => '',
				    'line' => 0,
				    'position' => 16,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be substituted',
				    'line' => 0,
				    'position' => 27,
				  ),
				)
            ),
           'a single empty resource_ext reference inside text' => array(
                'and there, some resource_ext:// will be substituted    ',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => '',
				    'line' => 0,
				    'position' => 16,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be substituted',
				    'line' => 0,
				    'position' => 31,
				  ),
				)
             ),

            'multiple resource references inside text' => array(
                'some resource://path/to/sth will be inserted there and resource://path/to/sthelse here ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 5,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be inserted there and ',
				    'line' => 0,
				    'position' => 31,
				  ),
				  3 => 
				  array (
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sthelse',
				    'line' => 0,
				    'position' => 59,
				  ),
				  4 => 
				  array (
				    'type' => 'text',
				    'value' => ' here ...',
				    'line' => 0,
				    'position' => 89,
				  ),
				)
            ),
            'multiple mixed resource references inside text' => array(
                'some resource_ext://path/to/sth will be inserted there and resource://path/to/sthelse here ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 5,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be inserted there and ',
				    'line' => 0,
				    'position' => 31,
				  ),
				  3 => 
				  array (
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sthelse',
				    'line' => 0,
				    'position' => 59,
				  ),
				  4 => 
				  array (
				    'type' => 'text',
				    'value' => ' here ...',
				    'line' => 0,
				    'position' => 89,
				  ),
				)
            ),
            'multiple mixed resource references inside text' => array(
                'some resource://path/to/sth will be inserted there and resource_ext://path/to/sthelse here ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 5,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be inserted there and ',
				    'line' => 0,
				    'position' => 27,
				  ),
				  3 => 
				  array (
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sthelse',
				    'line' => 0,
				    'position' => 55,
				  ),
				  4 => 
				  array (
				    'type' => 'text',
				    'value' => ' here ...',
				    'line' => 0,
				    'position' => 85,
				  ),
				)
            ),
            'multiple resource references inside text' => array(
                'some resource_ext://path/to/sth will be inserted there and resource_ext://path/to/sthelse here ...',
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
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 5,
				  ),
				  2 => 
				  array (
				    'type' => 'text',
				    'value' => ' will be inserted there and ',
				    'line' => 0,
				    'position' => 31,
				  ),
				  3 => 
				  array (
				    'type' => 'PagstractResource',
				    'name' => 'resource_ext',
				    'value' => 'path/to/sthelse',
				    'line' => 0,
				    'position' => 59,
				  ),
				  4 => 
				  array (
				    'type' => 'text',
				    'value' => ' here ...',
				    'line' => 0,
				    'position' => 89,
				  ),
				)
            ),

        );
    }
    
    
}
