<?php
/**
 * PHPagstract symbol resolver class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

namespace PHPagstractTest\Symbol;

use PHPagstract\Symbol\SymbolResolver;
use PHPagstract\Token\Tokens\TokenCollection;

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
    
}

