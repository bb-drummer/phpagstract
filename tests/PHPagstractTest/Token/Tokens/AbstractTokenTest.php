<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\Token;

class AbstractTokenTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        // clean-up: re-init default tokenizer setup
        new \PHPagstract\Token\MarkupTokenizer();;
    }
    
    public function testConstructorAndDefaults()
    {
        $abstractTokenMock = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken',
            array(Token::ELEMENT)
        );
        $this->assertEquals(0, $abstractTokenMock->getDepth());
        $this->assertNull($abstractTokenMock->getParent());
        $this->assertEquals(Token::ELEMENT, $abstractTokenMock->getType());
        $this->assertFalse($abstractTokenMock->isCDATA());
        $this->assertFalse($abstractTokenMock->isComment());
        $this->assertFalse($abstractTokenMock->isDocType());
        $this->assertFalse($abstractTokenMock->isPagstract());
        $this->assertTrue($abstractTokenMock->isElement());
        $this->assertFalse($abstractTokenMock->isPhp());
        $this->assertFalse($abstractTokenMock->isText());

        $newAbstractTokenMock = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken',
            array(Token::ELEMENT, $abstractTokenMock)
        );
        $this->assertEquals(1, $newAbstractTokenMock->getDepth());
        $this->assertEquals($abstractTokenMock, $newAbstractTokenMock->getParent());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionInConstructor()
    {
        $abstractTokenMock = $this->getMockForAbstractClass(
            'PHPagstract\\Token\\Tokens\\AbstractToken',
            array('asdf')
        );
    }
}
