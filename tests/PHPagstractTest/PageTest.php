<?php
namespace PHPagstractTest;

use PHPUnit_Framework_TestCase as TestCase;
use PHPagstract\Page\Page;
use PHPagstract\Page\PageAbstract;
use PHPagstract\Page\Model\PageModel;
use PHPagstract\Page\Resolver\FilepathResolver;
use PHPagstract\Page\Resolver\PropertyResolver;
use PHPagstract\Streams\InputStream;
use PHPagstract\Streams\DataStream;
use PHPagstract\Symbol\PropertyReferenceSymbolizer;
use PHPagstract\Page\Config\PageConfig;
use PHPagstract\Renderer\Renderer;

/**
 * PHPagstract page class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
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
        
        $mockPage = $this->createMock("\PHPagstract\Page\Page");
        $this->assertInstanceOf("\PHPagstract\Page\PageAbstract", $mockPage);
        $this->assertInstanceOf("\PHPagstract\Page\Page", $mockPage);
        
    }
    
    public function testSetGetPageModel() 
    {
        
        $page = new Page();

        $model  = $this->getMockForAbstractClass('PHPagstract\Page\Model\PageModel');
        $page->setPageModel($model);
        $testModel = $page->getPageModel();

        $this->assertInstanceOf('PHPagstract\Page\Model\PageModel', $testModel);
        $this->assertEquals($model, $testModel);
        
    }
    
    public function testInitAutoGeneratedPageModel() 
    {

        $page = $this->getTestPage();

        $testModel = $page->getPageModel();

        $this->assertInstanceOf('PHPagstract\Page\Model\PageModel', $testModel);
        
    }

    public function testSetGetInputStream() 
    {

        $page = $this->getTestPage();
        $page->setInputStream("my-content");
        $content = $page->getInputStream();
        $this->assertEquals("my-content", $content);
        
    }

    public function testGetEmptyInputStream() 
    {

        $page = new Page();
        $test = $page->getInputStream();
        $this->assertEmpty($test);
        $this->assertEquals('', $test);
        
    }

    public function testSetInputStreamWithFilename() 
    {

        $page = $this->getTestPage();
        $page->setInputStream('pageDefault.html');
        $test = $page->getInputStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        
    }
    
    public function testSetGetDataStream() 
    {
        
        $page = $this->getTestPage();
        $page->setDataStream('{ "root" : { "mykey" : "my-value" } }');
        $data = $page->getDataStream();
        //echo PHP_EOL; var_dump($page); echo PHP_EOL; 
        $this->assertInstanceOf("PHPagstract\Symbol\Symbols\Properties\RootProperty", $data);
        
    }

    public function testGetEmptyDataStream() 
    {

        $page = new Page();
        $test = $page->getDataStream();
        $this->assertEmpty($test);
        $this->assertNull($test);
        
    }

    public function testSetEmptyDataStreamCreatesDefault() 
    {

        $page = new Page();
        $page->setDataStream(null);
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithStringParameter() 
    {

        $page = $this->getTestPage();
        $page->setDataStream(file_get_contents(__DIR__.'/Symbol/Json/json-parse-test.json'));
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithResolvableFilenameParameter() 
    {

        $page = $this->getTestPage();
        $page->setDataStream('../pageOne.json');
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithFilenameParameter() 
    {

        $page = $this->getTestPage();
        $page->setDataStream(__DIR__.'/Symbol/Json/json-parse-test.json');
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithObjectParameterWithoutRoot() 
    {
        $data = (object)array(
            "aProperty" => "aValue",
            "otherProp" => "other value"
        );
        $page = $this->getTestPage();
        $page->setDataStream($data);
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithObjectParameterAndHasRoot() 
    {

        $data = (object)array(
                "root" => (object)array(
                        "aProperty" => "aValue",
                        "otherProp" => "other value"
                )
        );
        $page = $this->getTestPage();
        $page->setDataStream(json_decode(file_get_contents(__DIR__.'/Symbol/Json/json-parse-test.json')));
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithArrayParameterWithoutRoot() 
    {
        $data = array(
            "aProperty" => "aValue",
            "otherProp" => "other value"
        );
        $page = $this->getTestPage();
        $page->setDataStream($data);
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetDataStreamWithArrayParameterAndHasRoot() 
    {
        $data = array(
            "root" => array(
                "aProperty" => "aValue",
                "otherProp" => "other value"
            )
        );
        $page = $this->getTestPage();
        $page->setDataStream($data);
        $test = $page->getDataStream();
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\AbstractPropertySymbol', $test);
        $this->assertInstanceOf('PHPagstract\Symbol\Symbols\Properties\RootProperty', $test);
        
    }

    public function testSetConfigurationWithArrayParameter() 
    {
        $config = array(
            "configVar" => "value",
            "anotherVar" => "another value"
        );
        $page = $this->getTestPage();
        $page->setConfiguration($config);
        $testConfig = $page->getConfiguration();
        $this->assertNotNull($testConfig->configVar);
        $this->assertNotEmpty($testConfig->configVar);
        $this->assertEquals("value", $testConfig->configVar);
        $this->assertNotNull($testConfig->anotherVar);
        $this->assertNotEmpty($testConfig->anotherVar);
        $this->assertEquals("another value", $testConfig->anotherVar);
        
    }

    public function testSetConfigurationWithPageConfigParameter() 
    {
        $config = new PageConfig();
        $config->setConfig(
            array(
            "configVar" => "value",
            "anotherVar" => "another value"
            )
        );
        $page = $this->getTestPage();
        $page->setConfiguration($config);
        $testConfig = $page->getConfiguration();
        
        $this->assertNotNull($testConfig->configVar);
        $this->assertNotEmpty($testConfig->configVar);
        $this->assertEquals("value", $testConfig->configVar);
        $this->assertNotNull($testConfig->anotherVar);
        $this->assertNotEmpty($testConfig->anotherVar);
        $this->assertEquals("another value", $testConfig->anotherVar);
        
    }

    public function testGetAutoGeneratedRenderer() 
    {
        $page = $this->getTestPage();
        
        $testRenderer = $page->getRenderer();
        
        $this->assertNotNull($testRenderer);
        $this->assertInstanceOf('PHPagstract\Renderer\Renderer', $testRenderer);
        
    }

    public function testSetGetRenderer() 
    {
        $page = $this->getTestPage();
        
        $renderer = new Renderer();
        $page->setRenderer($renderer);
        $testRenderer = $page->getRenderer();
        
        $this->assertNotNull($testRenderer);
        $this->assertInstanceOf('PHPagstract\Renderer\Renderer', $testRenderer);
        $this->assertSame($renderer, $testRenderer);
        
    }
    
    
    //
    // simple 'output' test
    //

    /*
    public function testSimpleOutput()
    {
        
        $templateContent = 'this is a template';
        $jsonFile = __DIR__.'/Page/data/pageOne.json';
        $jsonData = json_decode(file_get_contents($jsonFile));
        
        $pagstractPage = $this->getTestPage();
        
        // set the input
        $pagstractPage->setInputStream($templateContent);
        $pagstractPage->setDataStream($jsonData);
        
        $testResult = $pagstractPage->output();

        $testRenderer = $pagstractPage->getRenderer();
        $this->assertNotNull($testRenderer);
        $this->assertInstanceOf('PHPagstract\Renderer\Renderer', $testRenderer);

        $this->assertNotNull($testResult);
        $this->assertNotEmpty($testResult);
        $this->assertEquals($templateContent, $testResult);
        
    }
    
    public function testSimpleOutputWithDebug()
    {
    
        $template = 'pageDefault.html';
        $jsonFile = __DIR__.'/Page/data/pageOne.json';
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
    
    }
    */
    
    
    //
    // some more traits tests
    //
    
    
    public function testInputStreamTrait()
    {
        $inputStream = new InputStream();
        
        $test = $inputStream->getStream();
        $this->assertNull($test);
        
        $inputStream->setStream('test');
        $test = $inputStream->getStream();
        $this->assertEquals("test", $test);

        $inputStream = new InputStream('test');
        $test = $inputStream->getStream();
        $this->assertEquals("test", $test);
        
    }
    
    public function testDataStreamTrait()
    {
        $dataStream = new DataStream();
        
        $test = $dataStream->getStream();
        $this->assertNull($test);
        
        $dataStream->setStream('test');
        $test = $dataStream->getStream();
        $this->assertEquals("test", $test);

        $dataStream = new DataStream('test');
        $test = $dataStream->getStream();
        $this->assertEquals("test", $test);
    }
    
    public function testGetFilepathResolverCreatesInstance()
    {
        $page = new Page();
        $test = $page->getFilepathResolver();

        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Page\Resolver\FilepathResolver', $test);
        
    }
    
    public function testSetGetFilepathResolver()
    {
        $page = new Page();
        
        $resolver = new FilepathResolver();
        $page->setFilepathResolver($resolver);
        $test = $page->getFilepathResolver();

        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Page\Resolver\FilepathResolver', $test);
        $this->assertEquals($resolver, $test);
        $this->assertSame($resolver, $test);
        
    }


    public function testGetPropertyResolverCreatesInstance()
    {

        $page = new Page();
        $test = $page->getPropertyResolver();
        
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Page\Resolver\PropertyResolver', $test);
         
    }
    
    public function testSetGetPropertyResolver()
    {

        $page = new Page();
         
        $resolver = new PropertyResolver();
        $page->setPropertyResolver($resolver);
        $test = $page->getPropertyResolver();
        
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Page\Resolver\PropertyResolver', $test);
        $this->assertEquals($resolver, $test);
        $this->assertSame($resolver, $test);
         
    }


    public function testGetPropertySymbolizerCreatesInstance()
    {

        $page = new Page();
        $test = $page->getPropertySymbolizer();
        
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\PropertyReferenceSymbolizer', $test);
         
    }
    
    public function testSetGetPropertySymbolizer()
    {

        $page = new Page();
         
        $symbolizer = new PropertyReferenceSymbolizer();
        $page->setPropertySymbolizer($symbolizer);
        $test = $page->getPropertySymbolizer();
        
        $this->assertNotNull($test);
        $this->assertNotEmpty($test);
        $this->assertInstanceOf('PHPagstract\Symbol\PropertyReferenceSymbolizer', $test);
        $this->assertEquals($symbolizer, $test);
        $this->assertSame($symbolizer, $test);
         
    }
    
    
    
    //
    // trait methods tests
    //
    
    /**
     * @dataProvider simpleGettersSettersDataProvider
     */
    public function testSimpleGettersSetters($varname, $value) 
    {
        $page = new Page();

        $setFunc = 'set'.ucfirst($varname);
        $getFunc = 'get'.ucfirst($varname);
        
        // setter test
        $page->$setFunc($value);
        $this->assertEquals($value, $page->$varname);
        
        // getter test
        $test = $page->$getFunc($value);
        $this->assertEquals($value, $test);
    }

    public function simpleGettersSettersDataProvider() 
    {
        return [
            'setGetThemeId (int)' => [
                "themeId",
                1
            ],
            'setGetThemeId (string)' => [
                "themeId",
                'blah'
            ],
            'setGetThemesDir' => [
                "themesDir",
                'path/to/themes/ids'
            ],
            'setGetBaseDir' => [
                "baseDir",
                'path/to/base'
            ],
                
            'setGetResourcesPath' => [
                "resourcesPath",
                'path/to/resources'
            ],
            'setGetResourcesExtPath' => [
                "resourcesExtPath",
                'path/to/resources_ext'
            ],
        ];
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
        
        $page->setBaseDir(__DIR__.'/Page/data/base/');
        $page->setThemesDir(__DIR__.'/Page/data/themes/');
        $page->setThemeId(1);
        
        $page->setResourcesPath('./');
        $page->setResourcesExtPath('./');
        
        return $page;
    }

}