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

use \PHPagstract\Page;
use \PHPagstract\PageAbstract;

class PageTest extends TestCase
{
	
    public function testInstantiateObject()
    {
    	try {
    		$page = new Page();
    		$className = get_class($page);
    	} catch (Exception $e) {
    		$page = null;
    		$className = null;
    	}

    	$this->assertNotNull($page);
    	$this->assertNotNull($className);
    	
		$mockPage = $this->createMock("\PHPagstract\Page");
    	$this->assertInstanceOf("\PHPagstract\PageAbstract", $mockPage);
    	$this->assertInstanceOf("\PHPagstract\Page", $mockPage);
    }
    
    public function testSetGetParser() {
    	$page = new Page();
    	$resolver = $this->createMock('PHPagstract\\Symbol\\SymbolResolver');
    	$tokenizer = $this->createMock('PHPagstract\Token\AbstractTokenizer');

    	$parser  = $this->getMockForAbstractClass('PHPagstract\\ParserAbstract', array($tokenizer, $resolver));
    	$page->setParser($parser);
    	$testParser = $page->getParser();

    	$this->assertInstanceOf('PHPagstract\\ParserAbstract', $testParser);
    	$this->assertEquals($parser, $testParser);
    }

}