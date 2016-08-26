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

class ParserTest extends TestCase
{
	
    public function testInstanciateObject()
    {
    	try {
    		$tpl = new Parser();
    		$className = get_class($tpl);
    	} catch (Exception $e) {
    		$tpl = null;
    		$className = null;
    	}

    	$this->assertNotNull($tpl);
    	$this->assertNotNull($className);
    	
		$mockPage = $this->createMock("\PHPagstract\Page");
    	$this->assertInstanceOf("\PHPagstract\PageAbstract", $mockPage);
    	$this->assertInstanceOf("\PHPagstract\Page", $mockPage);
    }

}