<?php

namespace PHPagstractTest\Renderer;

use PHPagstract\Renderer\Renderer;
use PHPagstract\Symbol\Symbols\SymbolCollection;
use PHPagstract\Page\Page;

/**
 * PHPagstract renderer class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class RendererTest extends \PHPUnit_Framework_TestCase
{
    
    public function testAbstractRenderSymbol() 
    {
        
        $symbolCollection = new SymbolCollection();
        $symbol1 = $this->createMock('PHPagstract\\Symbol\\Symbols\\AbstractTokenSymbol');
        $symbolCollection[] = $symbol1;
        
        $page = new Page();
        $renderer = new Renderer($page);
        
        $testRendering = $renderer->render($symbolCollection);
        $this->assertNotNull($testRendering);
        
    }
    
    public function testAbstractRenderSymbolWithResolvers() 
    {
        
        $symbolCollection = new SymbolCollection();
        $symbol1 = $this->createMock('PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractTile');
        $symbolCollection[] = $symbol1;
        
        $page = new Page();
        $renderer = new Renderer($page);
        
        $testRendering = $renderer->render($symbolCollection);
        $this->assertNotNull($testRendering);
        
    }
    
    
    
    //
    // (simple) symbol rendering tests
    //
    
    
    
    /**
     * @dataProvider renderMarkupSymbols
     */
    public function testRenderMarkupSymbols($markup, $assertion = 'contains', $needle = '', $debugMode = false) 
    {
        
        $jsonFile = __DIR__.'/../Page/data/pageOne.json';
        $jsonData = json_decode(file_get_contents($jsonFile));
        
        $pagstractPage = $this->getTestPage();
        
        // set the input
        $pagstractPage->setInputStream($markup);
        $pagstractPage->setDataStream($jsonData);
        
        $pagstractPage->getConfiguration()->debug($debugMode);
        
        $testResult = $pagstractPage->output();

        $this->assertNotNull($testResult);
        
        $assertFunction = 'assert'.ucfirst($assertion);
        if (method_exists($this, $assertFunction)) {
            $this->{$assertFunction}($needle, $testResult);
        }
        
    }
    
    
    
    public function renderMarkupSymbols() 
    {
        
        return array(
                
            // empty one
            'empty one' => array(
                '', // markup, pagstract
                'equals', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                '', // needle snippet
                false, // renderer debug mode
            ),
                
            // text only
            'text only' => array(
                'some text in here', // markup, pagstract
                'equals', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                'some text in here', // needle snippet
                false, // renderer debug mode
            ),
                
            // default HTML markup 
            'some text with markup' => array(
                'some text with <span>a tag</span> in here', // markup, pagstract
                'contains', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                '<span>a tag</span>', // needle snippet
                false, // renderer debug mode
            ),
                
            // default HTML markup with attributes
            'some text with HTML markup having attributes' => array(
                'some text with <span style="font-size: 10px;">a tag</span> in here', // markup, pagstract
                'contains', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                '<span style="font-size: 10px;">a tag</span>', // needle snippet
                false, // renderer debug mode
            ),
                
            // default HTML markup with debug output turned on
            'some text and HTML markup with debug output turned on' => array(
                'some text with <span>a tag</span> in here', // markup, pagstract
                'contains', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                '<span><!-- DEBUG: Array
(
    [name] => PagstractMarkup
    [line] => 0
    [position] => 15
    [token] => Array
        (
            [type] => PagstractMarkup
            [name] => span
            [value] => 
            [line] => 0
            [position] => 15
        )

)
 -->', // needle snippet
                true, // renderer debug mode
            ),
                
        );      
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

