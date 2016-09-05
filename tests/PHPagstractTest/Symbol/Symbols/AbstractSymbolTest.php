<?php

namespace PHPagstractTest\Symbol\Symbols;

use PHPagstract\Token\Tokens\Token;

/**
 *
 * @author bba
 *        
 */
class AbstractSymbolTest extends \PHPUnit_Framework_TestCase {
	
	public function testToArray() {
		$symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
		$testArray = $symbol->toArray();
		
		$this->assertEquals(array(), $testArray);
	}
	
	public function testSetGetToken() {
		$symbol = $this->getMockForAbstractClass('PHPagstract\\Symbol\\Symbols\\AbstractSymbol');
		$token  = $this->getMockForAbstractClass('PHPagstract\\Token\\Tokens\\AbstractToken', array(Token::TEXT, null, null));
	    
	    $symbol->setToken($token);
		$testToken = $symbol->getToken();
		
		$this->assertInstanceOf('PHPagstract\\Token\\Tokens\\AbstractToken', $testToken);
		$this->assertEquals($token, $testToken);
	}
	
}

