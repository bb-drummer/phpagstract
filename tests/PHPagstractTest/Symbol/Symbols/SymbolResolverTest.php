<?php

namespace PHPagstractTest\Symbol\Symbols;

use PHPagstract\Symbol\SymbolResolver;
use PHPagstract\Token\Tokens\TokenCollection;

/**
 *
 * @author bba
 *        
 */
class SymbolResolverTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @expectedException PHPagstract\Symbol\Exception\SymbolException
     */
    public function testResolveThrowsException() {
        
        $resolver = new SymbolResolver(true);
        $mockToken = $this->createMock('PHPagstract\\Token\\Tokens\\AbstractToken');
        $mockToken
            ->method('getType')
            ->willReturn('blah');
        $tokens = new TokenCollection();
        $tokens[] = $mockToken; 
        $symbols = $resolver->resolve($tokens);
    }
    
    /**
     * @expectedException PHPagstract\Symbol\Exception\SymbolException
     */
    public function testSetTokenTreeThrowsExceptionOnError() {
        $resolver = new SymbolResolver(true);
        $symbols = $resolver->setTokenTree(array());
    }
    
    public function testSetTokenReturnsEmptyTokenCollectionOnError() {
        $resolver = new SymbolResolver();
        $resolver->setTokenTree(array());
        $tokens = $resolver->getTokenTree();
        
        $this->assertInstanceOf('PHPagstract\\Token\\Tokens\\TokenCollection', $tokens);
        $this->assertEquals(0, $tokens->count());
    }
    
}

