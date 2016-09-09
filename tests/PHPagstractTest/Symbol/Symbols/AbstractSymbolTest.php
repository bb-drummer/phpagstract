<?php

namespace PHPagstractTest\Symbol\Symbols;

/**
 *
 * @author bba
 *        
 */
class AbstractSymbolTest extends \PHPUnit_Framework_TestCase {
    
    public function testToArray() {
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $testArray = $symbol->toArray();
        
        $this->assertEquals(array(
            'name' => 'Symbol',
            'line' => null,
            'position' => null,
        ), $testArray);
    }
    
    public function testToString() {
        $symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $testString = $symbol->toString();
        
        $this->assertEquals('', $testString);
    }
    
    public function testSetGetName() {
        $symbol = $this
            ->getMockBuilder('PHPagstract\\Symbol\\Symbols\\AbstractSymbol')
            ->setMethods(array('setName', 'getName'))
            ->getMockForAbstractClass()
        ;
        $symbol
            ->method('getName')
            ->willReturn('my-name')
        ;
        
        $symbol->setName("my-name");
        $testName = $symbol->getName();
        
        $this->assertEquals('my-name', $testName);
    }
    
}

