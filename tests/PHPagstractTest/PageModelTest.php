<?php
namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;

use PHPagstract\Page\Model\PageModel;
use PHPagstract\Page\Model\PageModelAbstract;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Symbol\GenericSymbolizer;

/**
 * PHPagstract page class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PageModelTest extends TestCase
{
    public function testInstantiateObjectNullSave()
    {
        
        try {
            $model = new PageModel();
            $className = get_class($model);
        } catch (Exception $e) {
            $model = null;
            $className = null;
        }

        $this->assertNotNull($model);
        $this->assertNotNull($className);
        $this->assertEquals("PHPagstract\Page\Model\PageModel", $className);
        
        $mockPageModel = $this->createMock("\PHPagstract\Page\Model\PageModel");
        $this->assertInstanceOf("\PHPagstract\Page\Model\PageModelAbstract", $mockPageModel);
        $this->assertInstanceOf("\PHPagstract\Page\Model\PageModel", $mockPageModel);
        
    }
    
    public function testSetGetParser() 
    {
        
        $model = new PageModel();
        $resolver = $this->createMock('PHPagstract\Symbol\GenericSymbolizer');
        $tokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');

        $parser  = $this->getMockForAbstractClass('PHPagstract\Parser\ParserAbstract', array($tokenizer, $resolver));
        $model->setParser($parser);
        $testParser = $model->getParser();

        $this->assertInstanceOf('PHPagstract\Parser\ParserAbstract', $testParser);
        $this->assertEquals($parser, $testParser);
        
    }
    
    public function testGetParserCreatesDefaultInstance() 
    {
        
        $model = new PageModel();
        /*$resolver = $this->createMock('PHPagstract\\Symbol\\GenericSymbolizer');
        $tokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');

        $parser  = $this->getMockForAbstractClass('PHPagstract\\ParserAbstract', array($tokenizer, $resolver));
        $model->setParser($parser);*/
        
        $testParser = $model->getParser();

        $this->assertInstanceOf('PHPagstract\Parser\ParserAbstract', $testParser);

        $testSymbolizer = $model->getSymbolizer();
        $this->assertNotNull($testSymbolizer);
        $this->assertNotEmpty($testSymbolizer);
        $this->assertInstanceOf('PHPagstract\Symbol\GenericSymbolizer', $testSymbolizer);
        
        $testTokenizer = $model->getTokenizer();
        $this->assertNotNull($testTokenizer);
        $this->assertNotEmpty($testTokenizer);
        $this->assertInstanceOf('PHPagstract\Token\AbstractTokenizer', $testTokenizer);
        $this->assertInstanceOf('PHPagstract\Token\MarkupTokenizer', $testTokenizer);
        
    }
    
    public function testSetGetPage() 
    {
        
        $model = new PageModel();

        $page  = $this->getMockForAbstractClass('PHPagstract\Page\Page');
        $model->setPage($page);
        $testPage = $model->getPage();

        $this->assertInstanceOf('PHPagstract\Page\Page', $testPage);
        $this->assertSame($page, $testPage);
        
    }
    
    /**
     * @expectedException PHPagstract\Exception
     */
    public function testGetPageException() 
    {
        $model = new PageModel();
        $model->throwOnError = true;
        $testPage = $model->getPage();
    }

    public function testGetTokenizerCreatesDefaultInstance()
    {
    
        $model = new PageModel();
        $test = $model->getTokenizer();
         
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Token\AbstractTokenizer', $test);
        $this->assertInstanceOf('PHPagstract\Token\MarkupTokenizer', $test);
    
    }
    
    public function testSetGetTokenizer()
    {
    
        $model = new PageModel();
    
        $tokenizer = new PagstractTokenizer();
        $model->setTokenizer($tokenizer);
        $test = $model->getTokenizer();
         
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Token\AbstractTokenizer', $test);
        $this->assertInstanceOf('PHPagstract\Token\PagstractTokenizer', $test);
        $this->assertEquals($tokenizer, $test);
        $this->assertSame($tokenizer, $test);
    
    }
    
    
    public function testGetSymbolizerCreatesDefaultInstance()
    {
    
        $model = new PageModel();
        $test = $model->getSymbolizer();
         
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\GenericSymbolizer', $test);
    
    }
    
    public function testSetGetSymbolizer()
    {
    
        $model = new PageModel();
    
        $symbolizer = new GenericSymbolizer();
        $model->setSymbolizer($symbolizer);
        $test = $model->getSymbolizer();
         
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\GenericSymbolizer', $test);
        $this->assertEquals($symbolizer, $test);
        $this->assertSame($symbolizer, $test);
    
    }
    
    public function testPageModelAbstractProcess()
    {
        
        $model = $this->getMockForAbstractClass('PHPagstract\Page\Model\PageModelAbstract');
        $page  = $this->getMockForAbstractClass('PHPagstract\Page\Page');
        $model->setPage($page);
        
        $test = $model->process();

        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $test);
        $this->assertCount(0, $test->toArray());
        
    }
    
    public function testPageModelProcess()
    {
        
        $model = $this->getMockForAbstractClass('PHPagstract\Page\Model\PageModel');
        $page  = $this->getMockForAbstractClass('PHPagstract\Page\Page');
        $model->setPage($page);
        
        $test = $model->process();

        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\SymbolCollection', $test);
        $this->assertCount(0, $test->toArray());
        
    }

    
}