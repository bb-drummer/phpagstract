<?php
/**
 * PHPagstract tile token symbol class
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Traits\FilepathResolverAwareTrait;
use PHPagstract\Parser\Parser;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Symbol\Symbols\SymbolCollection;

/**
 * PHPagstract tile (aka sub-template) token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractTile extends PagstractMarkup
{
    use FilepathResolverAwareTrait;
    
    /**
     * class constructor
     * 
     * @param AbstractTokenSymbol $parent
     * @param string              $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        parent::__construct($parent, $throwOnError);
    }

    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString()
    {
        $EOL = $this->config()->EOL();
    
        $result = '';
        if ($this->config()->debug()) {
            $result .= '<!-- DEBUG: ' . print_r($this->toArray(), true) . ' -->' . $EOL;
        }
        

        $attr = ($this->getAttributes());
        $filename = '';
        if (isset($attr['filename'])) {
            $filename = $this->getFilepathResolver()->resolveFilepath($attr['filename']);
            if ($filename !== null) {
    
                if ($this->config()->debugTileFilenames()) {
                    $result .= $EOL . '<!-- BEGIN: ' . $filename . ' -->' . $EOL;
                }

                $result .= $this->getTileFile($filename);
         
                if ($this->config()->debugTileFilenames()) {
                    $result .= $EOL . '<!-- END: ' . $filename . '" -->' . $EOL;
                }
        
            }
        }
        
         
        return $result;
    }
    
    /**
     * get sub-template, symbolize and render
     * 
     * @param  string $filename
     * @return string
     */
    private function getTileFile( $filename ) 
    {

        $EOL = $this->config()->EOL();
        $content = file_get_contents($filename);
        
        $parser = $this->getRenderer()->getPage()->getPageModel()->getParser();
        
        $tileSymbols = $parser->parse($content);
        
        $result = $this->getRenderer()->render($tileSymbols) . $EOL;
         
        return $result;
        
    }
    
}

