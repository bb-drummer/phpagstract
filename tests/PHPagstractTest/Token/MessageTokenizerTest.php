<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Token\MessageTokenizer;

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
        
        // clean-up: re-init default tokenizer setup
        new MarkupTokenizer();
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
                'msg://path/to/sth/only',
                array (
				  0 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => 'path/to/sth/only',
				    'line' => 0,
				    'position' => 0,
				  ),
				)
            ),
        	'a single msg reference inside text' => array(
                'some text with a msg://path/to/some.file reference ...',
                array (
				  0 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => 'path/to/some.file',
				    'line' => 0,
				    'position' => 0,
				  ),
				)
            ),
        	'a single msg reference inside text with just a backslash'  => array(
                'some text with a msg:/// reference ...',
                array (
				  0 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => '',
				    'line' => 0,
				    'position' => 0,
				  ),
				)
            ),
        	'a single msg reference inside text ending with backslash' => array(
                'some text with a msg://path/to/sth reference ...',
                array (
				  0 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 0,
				  ),
				)
            ),

        	'a single msg reference inside a tag' => array(
      			'some <tag with="msg://in/a/tag.jpg"> here...',
      			array (
        			0 =>
        			array (
						'type' => 'PagstractMessage',
        				'name' => 'msg',
       					'value' => 'in/a/tag.jpg',
        				'line' => 0,
        				'position' => 0,
        			),
        		)
        	),

        	'a single empty msg reference inside text' => array(
      			'and there, some msg:// will be substituted    ',
      			array (
        			0 =>
        			array (
						'type' => 'PagstractMessage',
        				'name' => 'msg',
       					'value' => '',
        				'line' => 0,
        				'position' => 0,
        			),
        		)
        	),

        	'multiple msg references inside text' => array(
      			'some msg://path/to/sth will be inserted there and msg://path/to/sthelse here ...',
      			array (
				  0 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => 'path/to/sth',
				    'line' => 0,
				    'position' => 0,
				  ),
				  1 => 
				  array (
				    'type' => 'PagstractMessage',
				    'name' => 'msg',
				    'value' => 'path/to/sthelse',
				    'line' => 0,
				    'position' => 23,
				  ),
				)
        	),

        );
    }
    
}
