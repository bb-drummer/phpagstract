<?php

namespace PHPagstractTest\Token\Tokens;

class PagstractCommentTest extends \PHPUnit_Framework_TestCase
{

    public function setUp() 
    {
        // clean-up: re-init default tokenizer setup
        new \PHPagstract\Token\PagstractTokenizer();
    }
    
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, $expectedValue, $expectedRemainingHtml)
    {
        $comment = new \PHPagstract\Token\Tokens\PagstractComment();
        $remainingHtml = $comment->parse($html);
        $this->assertEquals($expectedValue, $comment->getValue());
        $this->assertEquals($expectedRemainingHtml, $remainingHtml);
    }

    public function parseDataProvider()
    {
        return array(
            'simple' => array(
                '<!--- asdf -->',
                'asdf',
                ''
            ),
            'with element' => array(
                '<!--- asdf --><whoa />',
                'asdf',
                '<whoa />'
            ),
            'with whitespace' => array(
                '   <!---     asdf      -->    <whoa />',
                'asdf',
                '    <whoa />'
            ),
            'no whitespace' => array(
                '<!---asdf-->yo',
                'asdf',
                'yo'
            ),
            'two comments' => array(
                "<!--- asdf -->\n\n<!-- asdf -->",
                'asdf',
                "\n\n<!-- asdf -->"
            ),
            'parse error' => array(
                '<!--- asdf',
                null,
                ''
            )
        );
    }

    /**
     * @expectedException PHPagstract\Token\Exception\TokenizerException
     */
    public function testExceptionInParse()
    {
        $comment = new \PHPagstract\Token\Tokens\PagstractComment(null, true);
        $comment->parse('<!-- asdf');
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray)
    {
        $comment = new \PHPagstract\Token\Tokens\PagstractComment();
        $comment->parse($html);
        $this->assertEquals($expectedArray, $comment->toArray());
    }

    public function toArrayDataProvider()
    {
        return array(
            'simple' => array(
                '<!--- asdf -->',
                array(
                    'type' => 'PagstractComment',
                    'value' => 'asdf',
                    'line' => 0,
                    'position' => 0,
                    'name' => null
                )
            ),
            'multiline' => array(
                '<!--- as
                    df -->',
                array(
                    'type' => 'PagstractComment',
                    'value' => 'as
                    df',
                    'line' => 0,
                    'position' => 0,
                    'name' => null
                )
            )
        );
    }

}
