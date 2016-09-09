<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\ResourceTokenizer;

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
                array(
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
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/some.file',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource_ext reference inside text' => array(
                'some text with a resource_ext://path/to/some.file reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/some.file',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource reference inside text with just a backslash'  => array(
                'some text with a resource:/// reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => '/',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource_ext reference inside text with just a backslash' => array(
                'some text with a resource_ext:/// reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => '/',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource reference inside text ending with backslash' => array(
                'some text with a resource://path/to/sth/ reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sth/',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),
            'a single resource_ext reference inside text ending with backslash' => array(
                'some text with a resource_ext://path/to/sth/ reference ...',
                array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sth/',
                    'line' => 0,
                    'position' => 0,
                  ),
                )
            ),

            'a single resource reference inside a tag' => array(
                  'some <tag with="resource://in/a/tag.jpg"> here...',
                  array (
                    0 =>
                    array (
                        'type' => 'PagstractResource',
                        'name' => 'resource',
                           'value' => 'in/a/tag.jpg',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
            ),
               'a single resource_ext reference inside a tag' => array(
                'some <tag with="resource_ext://in/a/tag.jpg"> here...',
                array (
                    0 =>
                       array (
                           'type' => 'PagstractResource',
                        'name' => 'resource_ext',
                        'value' => 'in/a/tag.jpg',
                        'line' => 0,
                            'position' => 0,
                        ),
                )
               ),

            'a single empty resource reference inside text' => array(
                  'and there, some resource:// will be substituted    ',
                  array (
                    0 =>
                    array (
                        'type' => 'PagstractResource',
                        'name' => 'resource',
                           'value' => '',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
            ),
               'a single empty resource_ext reference inside text' => array(
                'and there, some resource_ext:// will be substituted    ',
                array (
                    0 =>
                       array (
                           'type' => 'PagstractResource',
                        'name' => 'resource_ext',
                        'value' => '',
                        'line' => 0,
                        'position' => 0,
                    ),
                )
               ),

            'multiple resource references inside text' => array(
                  'some resource://path/to/sth will be inserted there and resource://path/to/sthelse here ...',
                  array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sth',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sthelse',
                    'line' => 0,
                    'position' => 28,
                  ),
                )
            ),
            'multiple mixed resource references inside text' => array(
                  'some resource_ext://path/to/sth will be inserted there and resource://path/to/sthelse here ...',
                  array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sth',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sthelse',
                    'line' => 0,
                    'position' => 32,
                  ),
                )
            ),
            'multiple mixed resource references inside text' => array(
                  'some resource://path/to/sth will be inserted there and resource_ext://path/to/sthelse here ...',
                  array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource',
                    'value' => 'path/to/sth',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sthelse',
                    'line' => 0,
                    'position' => 28,
                  ),
                )
            ),
            'multiple resource references inside text' => array(
                  'some resource_ext://path/to/sth will be inserted there and resource_ext://path/to/sthelse here ...',
                  array (
                  0 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sth',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractResource',
                    'name' => 'resource_ext',
                    'value' => 'path/to/sthelse',
                    'line' => 0,
                    'position' => 32,
                  ),
                )
            ),

        );
    }
    
    
}
