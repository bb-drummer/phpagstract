<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtml($html, $expectedClassName)
    {
        $result = Tokens\TokenFactory::buildFromHtml($html);
        $this->assertInstanceOf($expectedClassName, $result);
    }

    public function buildFromHtmlDataProvider()
    {
        return array(
            array(
                'asdf',
                'PHPagstract\Token\Tokens\Text'
            ),
            array(
                '<asdf />',
                'PHPagstract\Token\Tokens\Element'
            ),
            array(
                '<!-- asdf -->',
                'PHPagstract\Token\Tokens\Comment'
            ),
            array(
                '<![CDATA[asdf]]>',
                'PHPagstract\Token\Tokens\CData'
            ),
            array(
                '<!DOCTYPE asdf >',
                'PHPagstract\Token\Tokens\DocType'
            ),
            array(
                '<?php asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            array(
                '<? asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            )
        );
    }

    public function testNoTypeFound()
    {
        $this->assertFalse(Tokens\TokenFactory::buildFromHtml('< asdfasdf'));
    }

    /**
     * @expectedException PHPagstract\Token\Exceptions\TokenMatchingException
     */
    public function testExceptionInBuildFromHtml()
    {
        Tokens\TokenFactory::buildFromHtml('< asdfasdf', null, true);
    }
}
