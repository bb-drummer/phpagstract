<?php
/**
 * file/path resolver awareness
 */
namespace PHPagstract\Traits;

use PHPagstract\Page\Resolver\FilepathResolver;

/**
 * file/path resolver awareness trait
 *
 * providing setter/getter methods for (basic) page building configuration
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait FilepathResolverAwareTrait
{
    
    /**
     * file/path resolver
     *
     * @var FilepathResolver
     */
    public $filepathResolver = null;

    /**
     * retrieve a file/path resolver instance
     * if not set, initialize new instance
     *
     * @return \PHPagstract\Page\Resolver\FilepathResolver $filepathResolver
     */
    public function getFilepathResolver() 
    {
        if (!($this->filepathResolver instanceof FilepathResolver)) {
            $this->filepathResolver = new FilepathResolver($this->throwOnError);
            $this->filepathResolver->setBaseDir($this->getBaseDir());
            $this->filepathResolver->setThemesDir($this->getThemesDir());
            $this->filepathResolver->setThemeId($this->getThemeId());
        }
        return $this->filepathResolver;
    }

    /**
     * set the file/path resolver instance
     * 
     * @param \PHPagstract\Page\Resolver\FilepathResolver $filepathResolver
     */
    public function setFilepathResolver($filepathResolver) 
    {
        $this->filepathResolver = $filepathResolver;
    }
}

