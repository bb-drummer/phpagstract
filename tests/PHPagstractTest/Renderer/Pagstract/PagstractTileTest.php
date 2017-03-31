<?php

namespace PHPagstractTest\Renderer\Pagstract;

use PHPagstract\Renderer\Renderer;
use PHPagstract\Symbol\Symbols\SymbolCollection;
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
class PagstractTileTest extends AbstractPagstractSymbolTest
{


    /**
     * @dataProvider renderMarkupSymbols
     */
    public function testRenderMarkupSymbols($markup, $assertion = 'contains', $needle = '', $debugMode = false) 
    {

        $pagstractPage = $this->getTestPage();

        // set the input
        $pagstractPage->setInputStream($markup);

        $pagstractPage->getConfiguration()->debug($debugMode);

        $testResult = $pagstractPage->output();

        $this->assertNotNull($testResult);

        $assertFunction = 'assert'.ucfirst($assertion);
        if (method_exists($this, $assertFunction)) {
            //echo PHP_EOL; var_dump($testResult); echo PHP_EOL;
            $this->{$assertFunction}($needle, $testResult);
        }

    }


    public function renderMarkupSymbols() 
    {

        return array(

                'single tile symbol' => array(
                        '<pma:tile filename="tests/tile_file_01.html"></pma:tile>', // markup, pagstract
                        'contains', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                        'some content in here...', // needle snippet
                        false, // renderer debug mode
                ),

                'tile symbol with tile-variable symbol' => array(
                        '<pma:tile filename="tests/tile_file_01.html"><pma:tileVariable>{
					"_tvMyVar" : "my value"
				}</pma:tileVariable</pma:tile>', // markup, pagstract
                        'contains', // assertion verb, ex: 'contains' -> ...->assertContains($needle, $result)
                        'some content in here...', // needle snippet
                        !true, // renderer debug mode
                ),

        );
    }



}

