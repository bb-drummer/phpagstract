<?php

namespace PHPagstractTest\Page;

use PHPagstract\Page\Page;

/**
 * PHPagstract generic page building tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class GenericPageBuildingTest extends \PHPUnit_Framework_TestCase
{

    
    //
    // simple 'output' test
    //
    


    /**
     * @dataProvider simpleOutputDataProvider
     */
    public function testSimpleOutputWithoutDebug($template, $jsonFile, $result, $debug = false)
    {
    
        //$template = 'pageDefault.html';
        //$jsonFile = __DIR__.'/../Page/data/pageOne.json';
        $jsonData = json_decode(file_get_contents($jsonFile));
        
        $pagstractPage = $this->getTestPage();
        
        // set the input
        $pagstractPage->setInputStream($template);
        $pagstractPage->setDataStream($jsonData);
        
        $testResult = $pagstractPage->output();

        $testRenderer = $pagstractPage->getRenderer();
        $this->assertNotNull($testRenderer);
        $this->assertInstanceOf('PHPagstract\Renderer\Renderer', $testRenderer);

        $this->assertNotNull($testResult);
        $this->assertNotEmpty($testResult);
        
        if ($debug) {
            echo PHP_EOL; print_r($testResult); echo PHP_EOL;
        }
        
    }


    /**
     * @dataProvider simpleOutputDataProvider
     */
    public function testSimpleOutputWithDebug($template, $jsonFile, $result, $debug = false)
    {
    
        //$template = 'pageDefault.html';
        //$jsonFile = __DIR__.'/../Page/data/pageOne.json';
        $jsonData = json_decode(file_get_contents($jsonFile));
    
        $pagstractPage = $this->getTestPage();
        $pagstractPage->getConfiguration()->debug(true);
        // set the input
        $pagstractPage->setInputStream($template);
        $pagstractPage->setDataStream($jsonData);
    
        $testResult = $pagstractPage->output();
    
        $this->assertNotNull($testResult);
        $this->assertNotEmpty($testResult);
        $this->assertContains("<!-- DEBUG:", $testResult);
        
        if ($debug) {
            echo PHP_EOL; print_r($testResult); echo PHP_EOL;
        }
    
    }
    
    public function simpleOutputDataProvider() 
    {
        return ([
                
            'pageDefault.html / pageOne.json' => [
                'pageDefault.html', // (markup) template file
                __DIR__.'/../Page/data/pageOne.json', // propertiey json file
                __DIR__.'/../Page/results/pageOne_pageDefault.html', // resulting markup
                false // debug output to cli?
            ],  
                
            'pageDefault.html / pageTwo.json' => [
                'pageDefault.html', // (markup) template file
                __DIR__.'/../Page/data/pageTwo.json', // propertiey json file
                __DIR__.'/../Page/results/pageTwo_pageDefault.html', // resulting markup
                false // debug output to cli?
            ],
                
            'pageSpecial.html / pageTwo.json' => [
                'pageSpecial.html', // (markup) template file
                __DIR__.'/../Page/data/pageTwo.json', // propertiey json file
                __DIR__.'/../Page/results/pageTwo_pageSpecial.html', // resulting markup
                !true // debug output to cli?
            ],  
                
        ]);
    } 
    
    //
    // test helpers
    //
    
    /**
     * create and retrieve pre-configured test page object
     * 
     * @return PHPagstract\Page\Page
     */
    private function getTestPage() 
    {
        $page = new Page();
        
        $page->setBaseDir(__DIR__.'/../Page/data/base/');
        $page->setThemesDir(__DIR__.'/../Page/data/themes/');
        $page->setThemeId(1);
        
        $page->setResourcesPath('./');
        $page->setResourcesExtPath('./');
        
        return $page;
    }

}

