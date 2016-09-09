<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        // clean-up: re-init default tokenizer setup
        new \PHPagstract\Token\MarkupTokenizer();;
    }
    
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, $expectedValue, $expectedRemainingHtml)
    {
        $text = new Text();
        $remainingHtml = $text->parse($html);
        $this->assertEquals($expectedValue, $text->getValue());
        $this->assertEquals($expectedRemainingHtml, $remainingHtml);
    }

    public function parseDataProvider()
    {
        return array(
            'simple' => array(
                'asdf',
                'asdf',
                ''
            ),
            'with element' => array(
                'asdf<whoa />',
                'asdf',
                '<whoa />'
            ),
            'with element and trailing whitespace' => array(
                'asdf      <whoa />',
                'asdf ',
                '<whoa />'
            ),
            'with comment' => array(
                '   asdf<!-- comment -->',
                ' asdf',
                '<!-- comment -->'
            )
        );
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray)
    {
        $text = new Text();
        $text->parse($html);
        $this->assertEquals($expectedArray, $text->toArray());
    }

    public function toArrayDataProvider()
    {
        return array(
            'simple' => array(
                'asdf',
                array(
                    'type' => 'text',
                    'value' => 'asdf',
                    'line' => 0,
                    'position' => 0
                )
            )
        );
    }
}

