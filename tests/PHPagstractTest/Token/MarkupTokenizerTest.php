<?php

namespace PHPagstractTest\Token;

use PHPagstract\Token\MarkupTokenizer;

/**
 * PHPagstract generic markup tokenizer class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class MarkupTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, array $expectedTokenArray, $debug = false)
    {
        
        $htmlTokenizer = new MarkupTokenizer();
        $tokens = $htmlTokenizer->parse($html);
        if ($debug) {
            var_dump($html, $tokens->toArray());
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
                    )
                )
            ),
            'contains php' => array(
                '<!-- comments --><div class="asdf1"><?php echo "asdf5"; ?></div>',
                array(
                    array(
                        'type' => 'comment',
                        'value' => 'comments',
                        'line' => 0,
                        'position' => 0
                    ),
                    array(
                        'type' => 'element',
                        'name' => 'div',
                        'line' => 0,
                        'position' => 17,
                        'attributes' => array(
                            'class' => 'asdf1'
                        ),
                        'children' => array(
                            array(
                                'type' => 'php',
                                'value' => 'echo "asdf5";',
                                'line' => 0,
                                'position' => 36
                            )
                        )
                    )
                )
            )
        );
    }

    /**
     * @dataProvider getPositionDataProvider
     */
    public function testGetPosition($html, $partialHtml, $expectedLine, $expectedPosition)
    {
        $htmlTokenizer = new MarkupTokenizer();
        $tokens = $htmlTokenizer->parse($html);
        $positionArray = MarkupTokenizer::getPosition($partialHtml);
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
