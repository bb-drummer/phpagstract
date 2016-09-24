<?php
namespace PHPagstractTest;

/**
 * PHPagstract filepath resolver class tests
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
    
    /**
     * @dataProvider simpleGettersSettersDataProvider
     */
    public function testSimpleGettersSetters($varname, $value) {
        $resolver = new FilepathResolver();

        $setFunc = 'set'.ucfirst($varname);
        $getFunc = 'get'.ucfirst($varname);
        
        $resolver->$setFunc($value);
        $this->assertEquals ($value, $resolver->$varname);
        
        $test = $resolver->$getFunc($value);
        $this->assertEquals ($value, $test);
    }

    public function simpleGettersSettersDataProvider() {
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
        ];
    }

    /**
     * @dataProvider findThemePathsDataProvider
     */
    public function testFindThemePaths($themeId, $expectedPaths) {
        $resolver = new FilepathResolver();
        $resolver->setBaseDir(__DIR__.'/data/base/');
        $resolver->setThemesDir(__DIR__.'/data/themes/');
        
        $resolver->setThemeId($themeId);
        
        $testPaths = $resolver->findThemePaths($themeId);
        $this->assertEquals ($expectedPaths, $testPaths);
    }
    
    public function findThemePathsDataProvider() {
        $dataDir = __DIR__.'/data/';
        return [
            'theme id: 1' => [
                1,
                [
                    $dataDir.'themes/1/'
                ]
            ],
            'theme id: 2' => [
                2,
                [
                    $dataDir.'themes/2.a/',
                    $dataDir.'themes/a/'
                ]
            ],
            'theme id: 3' => [
                3,
                [
                    $dataDir.'themes/3.2/',
                    $dataDir.'themes/2.a/',
                    $dataDir.'themes/a/'
                ]
            ],
        ];
    }

    /**
     * @dataProvider resolveFilepathDataProvider
     */
    public function testResolveFilepath($themeId, $file, $expectedPath) {
        $resolver = new FilepathResolver();
        
        $resolver->setBaseDir(__DIR__.'/data/base/');
        $resolver->setThemesDir(__DIR__.'/data/themes/');
        
        $resolver->setThemeId($themeId);
        
        $testPath = $resolver->resolveFilepath($file);
        $this->assertEquals ($expectedPath, $testPath);
    }
    
    public function resolveFilepathDataProvider() {
        $dataDir = __DIR__.'/data/';
        return [
                
            'theme id: 1, file to find in base/templates' => [
                1,
                'pageDefault.html',
                $dataDir.'base/templates/pageDefault.html'
            ],
            'theme id: 2, file to find in base/templates' => [
                2,
                'pageSpecial.html',
                $dataDir.'base/templates/pageSpecial.html'
            ],
                
            'theme id: 2, file to find in (parent) theme/n/templates' => [
                2,
                'global/header.html',
                $dataDir.'themes/a/templates/global/header.html'
            ],
            'theme id: 3, file to find in (sub-)(parent) theme/n/templates' => [
                3,
                'global/footer.html',
                $dataDir.'themes/3.2/templates/global/footer.html'
            ],
                
            'theme id: 1, file to find in base' => [
                1,
                'messages.properties',
                $dataDir.'base/messages.properties'
            ],
            'theme id: 2, file to find in theme' => [
                2,
                'messages.properties',
                $dataDir.'base/messages.properties'
            ],
            'theme id: 2, file to find in theme' => [
                3,
                'messages.properties',
                $dataDir.'themes/3.2/messages.properties'
            ],

            'theme id: 1, file not found' => [
                   1,
                'fileNotFound.html',
                  null
               ]
        ];
    }

}