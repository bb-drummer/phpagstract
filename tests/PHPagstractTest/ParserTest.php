<?php
namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;

use PHPagstract\Parser\Parser;
use PHPagstract\Parser\ParserAbstract;
use PHPagstract\Symbol\GenericSymbolizer;
use PHPagstract\Symbol\Exception\SymbolException;
use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Token\Tokens\TokenCollection;
use PHPagstract\Token\AbstractTokenizer;

/**
 * PHPagstract parser class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class ParserTest extends TestCase
{
    
    public function testInstanciateObject()
    {
        
        try {
            $mockTokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');
            $mockResolver = $this->createMock('PHPagstract\Symbol\GenericSymbolizer');
            $parser = new Parser($mockTokenizer, $mockResolver);
            $className = get_class($parser);
        } catch (Exception $e) {
            $parser = null;
            $className = null;
        }

        $this->assertNotNull($parser);
        $this->assertNotNull($className);
        
        $mockParser = $this->createMock("\PHPagstract\Parser\Parser");
        $this->assertInstanceOf("\PHPagstract\Parser\ParserAbstract", $mockParser);
        $this->assertInstanceOf("\PHPagstract\Parser\Parser", $mockParser);
        
    }
    
    public function testTokenize() 
    {
        
        $parser = $this->getParser();
        $tokens = $parser->tokenize('');

        $this->assertInstanceOf('PHPagstract\Token\Tokens\TokenCollection', $tokens);
        $this->assertEquals(0, $tokens->count());
        
    }
    
    public function testSymbolize() 
    {
        
        $parser = $this->getParser();
        $mockToken = $this->createMock('PHPagstract\Token\Tokens\Text');
        $token = new \PHPagstract\Token\Tokens\Text(null, false, null);
        $tokens = new TokenCollection();
        $tokens[] = $token; 
        
        $symbols = $parser->symbolize($tokens);

        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $symbols);
        $this->assertEquals(1, $symbols->count());
    }
    
    public function testSymbolizeStopsOnError() 
    {
        
        $parser = $this->getParser();
        $mockToken = $this->createMock('PHPagstract\Token\Tokens\Text');
        $token = new \PHPagstract\Token\Tokens\Text(null, false, null);
        $token->setType('BLAH');
        $tokens = new TokenCollection();
        $tokens[] = $token; 
        $symbols = $parser->symbolize($tokens);

        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $symbols);
        $this->assertEquals(0, $symbols->count());
        
    }
    
    /**
     * @expectedException PHPagstract\Symbol\Exception\SymbolException
     */
    public function testSymbolizeThrowsException() 
    {
        
        $parser = $this->getParser(true);
        $mockToken = $this->createMock('PHPagstract\Token\Tokens\AbstractToken');
        $mockToken
            ->method('getType')
            ->willReturn('blah');
        $tokens = new TokenCollection();
        $tokens[] = $mockToken; 
        $symbols = $parser->symbolize($tokens);
        
    }
    
    public function testParse() 
    {
        
        $parser = $this->getParser();
        
        $compiled = $parser->parse('');

        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $compiled);
        $this->assertCount(0, $compiled->toArray());
        //$this->assertEquals($compiled, '');
        
    }
    
    public function testSetGetResolver() 
    {
        
        $parser = $this->getParser();
        $resolver = $this->createMock('PHPagstract\Symbol\GenericSymbolizer');
        $parser->setResolver($resolver);
        $testResolver = $parser->getResolver();

        $this->assertInstanceOf('PHPagstract\Symbol\GenericSymbolizer', $testResolver);
        $this->assertEquals($resolver, $testResolver);
        
    }
    
    /**
     * @expectedException PHPagstract\Parser\Exception
     */
    public function testGetResolverException() 
    {
        $parser = new Parser(null, null, true);
        $parser->getResolver();
    }
    
    /**
     * @expectedException PHPagstract\Parser\Exception
     */
    public function testGetTokenizerException() 
    {
        $parser = new Parser(null, null, true);
        $parser->getTokenizer();
    }
    
    /**
     * @dataProvider files2parseDataProvider
     */
    public function testParseMarkup($tokenizer, $filename, $throwOnError = false) 
    {
        $mockResolver  = new GenericSymbolizer($throwOnError); 
        $mockTokenizer = new $tokenizer($throwOnError);
        
        $parser = new Parser($mockTokenizer, $mockResolver, $throwOnError);
        $html = file_get_contents($filename);

        $pagstracttokens = $parser->tokenize($html);
        $pagstractsymbols = $parser->symbolize($pagstracttokens);
        
        
        $this->assertEquals($pagstracttokens->count(), $pagstractsymbols->count());
        
    }

    /**
     * create a parser object
     *
     * @return ParserAbstract
     */
    private function getParser($throwOnError = false) 
    {
        
        $mockTokens    = new TokenCollection();
        $mockTokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');
        $mockTokenizer
            ->method('parse')
            ->willReturn($mockTokens);

        $mockSymbols    = new SymbolCollection();
        $mockResolver  = new GenericSymbolizer($throwOnError); 
        
        $parser = new Parser($mockTokenizer, $mockResolver, $throwOnError);
        
        return $parser;
    }
    
    public function files2parseDataProvider() 
    {
        return array(
            "HTML file 01" => array(
                "tokenizer" => "PHPagstract\Token\MarkupTokenizer",
                "filename" => __DIR__."/Token/Html/bootstrap-com.html",
            ),
            "Pagstract file 01" => array(
                "tokenizer" => "PHPagstract\Token\PagstractTokenizer",
                "filename" => __DIR__."/Token/Html/pagstract-test.html",
            ),
        );
    }
}