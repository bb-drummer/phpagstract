<?php
/**
 * PHPagstract symbol collection class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

namespace PHPagstractTest\Symbol;

use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\Symbols\AbstractSymbol;

class SymbolCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndDefaults()
    {
        $collection = new SymbolCollection();
        $this->assertEquals(array(), $collection->toArray());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->isEmpty());
        $this->assertFalse(isset($collection[0]));
    }

    public function testArrayAccess()
    {
        $collection = new SymbolCollection();
        $this->assertEquals(0, $collection->count());
        $this->assertFalse(isset($collection[0]));
        
        $symbol = $this->createMock('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $collection[0] = $symbol;
        $this->assertEquals(1, $collection->count());
        $this->assertFalse($collection->isEmpty());
        $this->assertTrue(isset($collection[0]));
        
        $newSymbol = $collection[0];
        $this->assertEquals($symbol, $newSymbol);
        $this->assertEquals(1, $collection->count());
        
        unset($collection[0]);
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionInOffsetSet()
    {
        $collection = new SymbolCollection();
        $collection[0] = 5;
    }
    
    public function testToArray()
    {
        $collection = new SymbolCollection();
        $symbol1 = $this->createMock('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $symbol1->method("toArray")->willReturn(array());
        $symbol2 = $this->createMock('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
        $symbol2->method("toArray")->willReturn(array());
        $collection[] = $symbol1;
        $collection[] = $symbol2;
        
        $testArray = $collection->toArray();
        $expectedArray = array( $symbol1->toArray(), $symbol2->toArray() );
        
        $this->assertEquals($expectedArray, $testArray);
    }
}
