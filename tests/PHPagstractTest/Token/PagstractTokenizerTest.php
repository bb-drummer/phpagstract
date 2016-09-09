<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\PagstractTokenizer;

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
            )
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
                '<html><head><title>Asdf1</title></head><body>Yo!</body></html>',
                '<head><title>Asdf1</title></head><body>Yo!</body></html>',
                0,
                6
            ),
            'single line - 2' => array(
                '<html><head><title>Asdf1</title></head><body>Yo!</body></html>',
                '<body>Yo!</body></html>',
                0,
                39
            ),
            'multiple lines - 1' => array(
                '<html>
    <head>
        <title>Asdf1</title>
    </head>
    <body>Yo!</body>
</html>',
                '<head>
        <title>Asdf1</title>
    </head>
    <body>Yo!</body>
</html>',
                1,
                5
            ),
            'multiple lines - 1' => array(
                '<html>
    <head>
        <title>Asdf1</title>
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
