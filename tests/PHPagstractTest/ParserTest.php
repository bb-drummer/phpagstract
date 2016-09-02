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

class ParserTest extends TestCase
{
	
    public function testInstanciateObject()
    {
    	try {
    		$parser = new Parser();
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
    
    public function testParse () {
    	$parser = new Parser();
    	
    	$compiled = $parser->parse('');
    	
    	$this->assertEquals($compiled, '');
    }

}