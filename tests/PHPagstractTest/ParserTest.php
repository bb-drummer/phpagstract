<?php
/**
 * PHPagstract page class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;

use \PHPagstract\Parser;
use PHPagstract\Token\Tokens\TokenCollection;
use PHPagstract\Token\AbstractTokenizer;
use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Symbol\SymbolResolver;
use PHPagstract\ParserAbstract;

class ParserTest extends TestCase
{
	
    public function testInstanciateObject()
    {
    	try {
    		$mockTokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');
    		$mockResolver = $this->createMock('PHPagstract\Symbol\SymbolResolver');
    		$parser = new Parser( $mockTokenizer, $mockResolver );
    		$className = get_class($parser);
    	} catch (Exception $e) {
    		$parser = null;
    		$className = null;
    	}

    	$this->assertNotNull($parser);
    	$this->assertNotNull($className);
    	
		$mockParser = $this->createMock("\PHPagstract\Parser");
    	$this->assertInstanceOf("\PHPagstract\ParserAbstract", $mockParser);
    	$this->assertInstanceOf("\PHPagstract\Parser", $mockParser);
    }
    
    public function testTokenize () {
    	$parser = $this->getParser();
    	$tokens = $parser->tokenize('');

    	$this->assertInstanceOf('PHPagstract\Token\Tokens\TokenCollection', $tokens);
    }
    
    public function testSymbolize () {
    	$parser = $this->getParser();
    	$mockTokens = $this->createMock('PHPagstract\Token\Tokens\TokenCollection');
    	$symbols = $parser->symbolize($mockTokens);

    	$this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $symbols);
    }
    
    public function testParse () {
    	$parser = $this->getParser();
    	
    	$compiled = $parser->parse('');
    	
    	$this->assertEquals($compiled, '');
    }
    
    public function testSetGetResolver () {
    	$parser = $this->getParser();
    	$resolver = $this->createMock('PHPagstract\\Symbol\\SymbolResolver');
    	$parser->setResolver($resolver);
    	$testResolver = $parser->getResolver();

    	$this->assertInstanceOf('PHPagstract\\Symbol\\SymbolResolver', $testResolver);
    	$this->assertEquals($resolver, $testResolver);
    }

    /**
     * create a parser object
     * @return ParserAbstract
     */
    private function getParser() {
    	$mockTokens    = $this->createMock('PHPagstract\Token\Tokens\TokenCollection');
    	$mockTokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');
    	$mockTokenizer
    		->method('parse')
    		->willReturn($mockTokens);
    	
    	$mockResolver  = $this->createMock('PHPagstract\Symbol\SymbolResolver');
    	/*$mockResolver
    		->method('parse')
    		->willReturn($mockTokens);*/
    	
    	$parser = new Parser( $mockTokenizer, $mockResolver );
    	
    	return $parser;
    }
}