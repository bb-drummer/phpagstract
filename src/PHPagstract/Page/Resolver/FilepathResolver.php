<?php
namespace PHPagstract\Page\Resolver;

/**
 * PHPagstract theme-/file-path resolver class tests
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class FilepathResolver {
    
    /**
     * path to base template set
     * @var string 
     */
    public $baseDir = "path/to/base"; // ..../shop/base/
    

    /**
     * path to themes template set
     * @var string
     */
    public $themesDir = "path/to/themes"; // ..../shop/mandant/{n.m}/
    
    /**
     * selected theme
     * @var int
     */
    public $themeId = null;
    
    /**
     */
    public function __construct( ) {
    }
    
    /**
     * resolve path to (themed) $filename
     * 
     * @param string $filename
     * @return string the complete compiled path
     */
    public function resolveFilepath($filename) {
        $themeId = $this->getThemeId();
        $paths = $this->findThemePaths($themeId);
        // finally add base/
        $paths[] = $this->getBaseDir();
        foreach ($paths as $idx => $path) {
            $filepath = $path.$filename;
            $tplFilepath = $path.'templates/'.$filename;
            if (file_exists($filepath)) {
                return $filepath;
            } else if (file_exists($tplFilepath)) {
                return $tplFilepath;
            }
        }
        return null;
    }
    
    /**
     * find theme folders to parse by theme-id
     * 
     * example:
     * 
     * dirs:
     *  themes/1/
     *  themes/2.a/
     *  themes/3.2/
     *  themes/a
     * 
     * id: 1 => [
     *  "themes/1/",
     * ]
     * 
     * id: 2 => [
     *  "themes/2.a/",
     *  "themes/a/",
     * ]
     * 
     * id: 3 => [
     *  "themes/3.2/",
     *  "themes/2.a/",
     *  "themes/a/",
     * ]
     * 
     * @param integer $themeId
     * @return array of theme related folders
     */
    public function findThemePaths($themeId, $result = []) {
        $themesDir = $this->getThemesDir();
        $paths = scandir($themesDir);
        
        // look for .../themes/{themeId.parent}/
        foreach ($paths as $idx => $file) {
            if (!in_array($file, ['.', '..']) && is_dir($themesDir.$file)) {
                if (((($file == $themeId) && is_dir($themesDir.$file)) || (strpos($file, $themeId.'.') === 0)) && !in_array($file, $result)) {
                    $result[] = $themesDir.$file."/";
                    if ((strpos($file, '.') !== false)) {
                        $ids = explode('.', $file, 2);
                        $parents = $this->findThemePaths($ids[1], $result);
                        foreach ($parents as $key => $value) {
                            if (!in_array($value, $result)) {
                                $result[] = $value;
                            }
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * @return string $baseDir
     */
    public function getBaseDir() {
        return $this->baseDir;
    }

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir) {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string $themesDir
     */
    public function getThemesDir() {
        return $this->themesDir;
    }

    /**
     * @param string $themesDir
     */
    public function setThemesDir($themesDir) {
        $this->themesDir = $themesDir;
    }

    /**
     * @return int $themeId
     */
    public function getThemeId() {
        return $this->themeId;
    }

    /**
     * @param int $themeId
     */
    public function setThemeId($themeId) {
        $this->themeId = $themeId;
    }

        
}

