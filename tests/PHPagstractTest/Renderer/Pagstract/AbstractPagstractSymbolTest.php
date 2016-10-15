<?php

namespace PHPagstractTest\Renderer\Pagstract;

use PHPagstract\Renderer\Renderer;
use PHPagstract\Page\Page;

/**
 * PHPagstract abstract symbol tests class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class AbstractPagstractSymbolTest extends \PHPUnit_Framework_TestCase
{
    
    //
    // ... includes some test helpers
    //
    
    /**
     * create and retrieve pre-configured test page object
     * 
     * @return PHPagstract\Page\Page
     */
    protected function getTestPage() 
    {
        $page = new Page();
        
        $page->setBaseDir(__DIR__.'/../../Page/data/base/');
        $page->setThemesDir(__DIR__.'/../../Page/data/themes/');
        $page->setThemeId(1);
        
        $page->setResourcesPath('./');
        $page->setResourcesExtPath('./');
        
        $jsonFile = __DIR__.'/../../Page/data/pageOne.json';
        $jsonData = json_decode(file_get_contents($jsonFile));
        $page->setDataStream($jsonData);
        
        
        return $page;
    }
    
    
    
    
}

