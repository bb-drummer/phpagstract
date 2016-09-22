<?php
namespace PHPagstractTest;

/**
 * PHPagstract page class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\Page\Resolver\FilepathResolver;

class FilepathResolverTestTest extends TestCase
{
    
    public function testInstantiateObject()
    {
        
        try {
            $resolver = new FilepathResolver();
            $className = get_class($resolver);
        } catch (Exception $e) {
            $resolver = null;
            $className = null;
        }

        $this->assertNotNull($resolver);
        $this->assertNotNull($className);
        $this->assertInstanceOf("\\PHPagstract\\Page\\Resolver\\FilepathResolver", $resolver);
        
        $mockResolver = $this->createMock("\\PHPagstract\\Page\\Resolver\\FilepathResolver");
        $this->assertInstanceOf("\\PHPagstract\\Page\\Resolver\\FilepathResolver", $mockResolver);
        
    }

}