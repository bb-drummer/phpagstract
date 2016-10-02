<?php

namespace PHPagstractTest\Token;

/**
 *
 * @author bba
 */
class AbstractTokenizerTest extends \PHPUnit_Framework_TestCase
{

    public function testParse() 
    {
        
        $tokenizer = $this->getMockForAbstractClass('PHPagstract\Token\AbstractTokenizer');
        $tokens = $tokenizer->parse('');
        
        $this->assertInstanceOf('PHPagstract\Token\Tokens\\TokenCollection', $tokens);
        $this->assertEquals(array(), $tokens->toArray());
        
    }
    
    public function testGetPosition() 
    {
        
        $tokenizer = $this->getMockForAbstractClass('PHPagstract\Token\AbstractTokenizer');
        $testPosition = $tokenizer->getPosition('');
        $expectedPosition = array(
            'line' => 0,
            'position' => 0
        );
        
        $this->assertEquals($expectedPosition, $testPosition);
        
    }
}

