<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\CData;

class CDataTest extends \PHPUnit_Framework_TestCase
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
        $cData = new CData();
        $remainingHtml = $cData->parse($html);
        $this->assertEquals($expectedValue, $cData->getValue());
        $this->assertEquals($expectedRemainingHtml, $remainingHtml);
    }

    public function parseDataProvider()
    {
        return array(
            'simple' => array(
                '<![CDATA[asdf]]>',
                'asdf',
                ''
            ),
            'with element' => array(
                '<![CDATA[asdf]]><whoa />',
                'asdf',
                '<whoa />'
            ),
            'with whitespace' => array(
                '    <![CDATA[      asdf     ]]>    <whoa />',
                'asdf',
                '    <whoa />'
            ),
            'parse error' => array(
                '<![CDATA[      asdf',
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
        $cData = new CData(null, true);
        $cData->parse('<![CDATA[asdf');
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray)
    {
        $cData = new CData();
        $cData->parse($html);
        $this->assertEquals($expectedArray, $cData->toArray());
    }

    public function toArrayDataProvider()
    {
        return array(
            'simple' => array(
                '<![CDATA[asdf]]>',
                array(
                    'type' => 'cdata',
                    'value' => 'asdf',
                    'line' => 0,
                    'position' => 0
                )
            )
        );
    }
}

