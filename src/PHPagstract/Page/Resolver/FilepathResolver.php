<?php
namespace PHPagstract\Page\Resolver;

use PHPagstract\Traits\ThemeConfigurationTrait;

/**
 * PHPagstract theme-/file-path resolver class
 *
 * methods to resolve file/path references to find a file within a special 
 * theme directory to support a parent-to-child-like item inheritance 
 * 
 * inheritance is based upon the item order in determined search-path array 
 * directories for themes should be created according to the pattern:
 *     ".../path/to/themes/{themeId[.parentThemeId]}/"
 *     
 * however, {FilepathResolver::$baseDir} will always be the last path to 
 * look for a file or path
 * 
 * example:
 * 
 * themes dir structure:
 *  themes/1/
 *  themes/2.a/
 *  themes/3.2/
 *  themes/a
 * 
 * search-paths for theme-id: 1 => [
 *  "themes/1/",
 * ]
 * 
 * search-paths for theme-id: 2 => [
 *  "themes/2.a/",
 *  "themes/a/",
 * ]
 * 
 * search-paths for theme-id: 3 => [
 *  "themes/3.2/",
 *  "themes/2.a/",
 *  "themes/a/",
 * ]
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class FilepathResolver
{
    
    use ThemeConfigurationTrait;
    
    
    /**
     * throw exception on error?
     *
     * @var boolean
     */
    protected $throwOnError;
    
    /**
     * @param boolean $throwOnError throw exception on error?
     */
    public function __construct($throwOnError = false)
    {
        $this->throwOnError = $throwOnError;
    }
    
    
    /**
     * resolve path to (themed) $filename
     * 
     * @param  string $filename
     * @return string the complete compiled path
     */
    public function resolveFilepath($filename) 
    {
        $themeId = $this->getThemeId();
        $paths = $this->findThemePaths($themeId);
        // finally add base/
        $paths[] = $this->getBaseDir();
        foreach ($paths as $path) {
            $filepath = $path.$filename;
            $tplFilepath = $path.'templates/'.$filename;
            if (strlen($filepath) > PHP_MAXPATHLEN) {
                return null;
            }
            if (is_file($filepath) && file_exists($filepath)) {
                return $filepath;
            } else if (is_file($tplFilepath) && file_exists($tplFilepath)) {
                return $tplFilepath;
            }
        }
        return null;
    }
    
    /**
     * find theme and parent-theme folders by given theme-id
     * 
     * @param  integer $themeId
     * @return array of theme related folders
     */
    public function findThemePaths($themeId, $result = []) 
    {
        $themesDir = $this->getThemesDir();
        $paths = scandir($themesDir);
        
        // look for .../themes/{themeId.parent}/
        foreach ($paths as $file) {
            if (!in_array($file, ['.', '..']) && is_dir($themesDir.$file)) {
                if (((($file == $themeId) && is_dir($themesDir.$file)) || (strpos($file, $themeId.'.') === 0)) && !in_array($file, $result)) {
                    $result[] = $themesDir.$file."/";
                    if ((strpos($file, '.') !== false)) {
                        $ids = explode('.', $file, 2);
                        $parents = $this->findThemePaths($ids[1], $result);
                        foreach ($parents as $value) {
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

        
}

