<?php

namespace PHPagstract\Traits;

/**
 * page configuration trait
 * 
 * providing setter/getter methods for (basic) page building configuration 
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait ThemeConfigurationTrait
{
    
    /**
     * selected theme
     *
     * @var integer
     */
    public $themeId = null;
    
    /**
     * path to base template set
     * ".../path/to/baseTheme/"
     *
     * @var string 
     */
    public $baseDir = "path/to/base";
    

    /**
     * path to themes template set
     * ".../path/to/themes/{themeId[.parentThemeId]}/"
     *
     * @var string
     */
    public $themesDir = "path/to/themes";



    /**
     * @return string $baseDir
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }
    
    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }
    
    /**
     * @return string $themesDir
     */
    public function getThemesDir()
    {
        return $this->themesDir;
    }
    
    /**
     * @param string $themesDir
     */
    public function setThemesDir($themesDir)
    {
        $this->themesDir = $themesDir;
    }
    
    /**
     * @return int $themeId
     */
    public function getThemeId()
    {
        return $this->themeId;
    }
    
    /**
     * @param int $themeId
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;
    }
    
}

