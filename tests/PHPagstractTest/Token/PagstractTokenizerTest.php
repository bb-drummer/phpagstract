<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\PagstractTokenizer;

/**
 * PHPagstract Pagstract Markup tokenizer class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParseHTMLOnly($html, array $expectedTokenArray, $debug = false)
    {
        
        $htmlTokenizer = new PagstractTokenizer();
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
                'asdf',
                array(
                    array(
                        'type' => 'text',
                        'value' => 'asdf',
                        'line' => 0,
                        'position' => 0
                    )
                )
            ),
            'token matching error' => array(
                'asdf  <',
                array(
                    array(
                        'type' => 'text',
                        'value' => 'asdf ',
                        'line' => 0,
                        'position' => 0
                    ),
                    array(
                        'type' => 'PagstractMarkup',
                        'name' => null,
                        'value' => null,
                        'line' => 0,
                        'position' => 6
                    )
                )
            ),
            'contains php' => array(
                '<!-- comments --><div class="asdf1"><?php echo "asdf5"; ?></div>',
                array (
                  0 => 
                  array (
                    'type' => 'comment',
                    'value' => 'comments',
                    'line' => 0,
                    'position' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'PagstractMarkup',
                    'name' => 'div',
                    'value' => NULL,
                    'line' => 0,
                    'position' => 17,
                    'attributes' => 
                    array (
                      'class' => 'asdf1',
                    ),
                  ),
                  2 => 
                  array (
                    'type' => 'php',
                    'value' => 'echo "asdf5";',
                    'line' => 0,
                    'position' => 36,
                  ),
                  3 => 
                  array (
                    'type' => 'PagstractMarkup',
                    'name' => '',
                    'value' => NULL,
                    'line' => 0,
                    'position' => 58,
                    'attributes' => 
                    array (
                      'div' => true,
                    ),
                  ),
                )
            ),
            'token nesting' => array(
                '<pma:switch pma:name=".prop">default text <object pma:case="val">${.prop}  </object>    </pma:switch>  ',
				array (
				  0 => 
				  array (
				    'type' => 'PagstractSwitch',
				    'name' => 'pma:switch',
				    'value' => NULL,
				    'line' => 0,
				    'position' => 0,
				    'attributes' => 
				    array (
				      'pma:name' => '.prop',
				    ),
				    'children' => 
				    array (
				      0 => 
				      array (
				        'type' => 'text',
				        'value' => 'default text ',
				        'line' => 0,
				        'position' => 29,
				      ),
				      1 => 
				      array (
				        'type' => 'PagstractObject',
				        'name' => 'object',
				        'value' => NULL,
				        'line' => 0,
				        'position' => 42,
				        'attributes' => 
				        array (
				          'pma:case' => 'val',
				        ),
				        'children' => 
				        array (
				          0 => 
				          array (
				            'type' => 'text',
				            'value' => '${.prop} ',
				            'line' => 0,
				            'position' => 65,
				          ),
				        ),
				      ),
				    ),
				  ),
				)
            ),
        );
    }

    /**
     * @dataProvider getPositionDataProvider
     */
    public function testGetPosition($html, $partialHtml, $expectedLine, $expectedPosition)
    {
        
        $htmlTokenizer = new PagstractTokenizer();
        $tokens = $htmlTokenizer->parse($html);
        $positionArray = PagstractTokenizer::getPosition($partialHtml);
        $this->assertEquals($expectedLine, $positionArray['line']);
        $this->assertEquals($expectedPosition, $positionArray['position']);
        
    }

    public function getPositionDataProvider()
    {
        return array(
            'single line - 1' => array(
                '<html><head><title>asdf1</title></head><body>Yo!</body></html>',
                '<head><title>asdf1</title></head><body>Yo!</body></html>',
                0,
                6
            ),
            'single line - 2' => array(
                '<html><head><title>asdf1</title></head><body>Yo!</body></html>',
                '<body>Yo!</body></html>',
                0,
                39
            ),
            'multiple lines - 1' => array(
                '<html>
    <head>
        <title>asdf1</title>
    </head>
    <body>Yo!</body>
</html>',
                '<head>
        <title>asdf1</title>
    </head>
    <body>Yo!</body>
</html>',
                1,
                5
            ),
            'multiple lines - 1' => array(
                '<html>
    <head>
        <title>asdf1</title>
    </head>
    <body>Yo!</body>
</html>',
                '<body>Yo!</body>
</html>',
                4,
                5
            )
        );
    }
}
