<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\PagstractPropertyReferenceText;

class PagstractPropertyReferenceTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, $expectedValue, $expectedRemainingHtml)
    {
        $text = new PagstractPropertyReferenceText();
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
            'with reference' => array(
                'asdf ${.var}',
                'asdf ',
                '${.var}'
            ),
            'with reference and trailing whitespace' => array(
                'asdf      ${.var}',
                'asdf ',
                '${.var}'
            ),
            'text with leading whitespace' => array(
                '   asdf ${.var}',
                ' asdf ',
                '${.var}'
            ),
            'leading whitespace with token' => array(
                '      ${.var}',
                ' ',
                '${.var}'
            )
        );
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray)
    {
        $text = new PagstractPropertyReferenceText();
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

