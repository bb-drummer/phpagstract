<?php
/**
 * page configuration object
 */
namespace PHPagstract\Page\Config;

use PHPagstract\AbstractConfiguration;

/**
 * page configuration object class
 *
 * providing th page's configuration values and flags
 *  
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PageConfig extends AbstractConfiguration
{
    
    /**
     * path to base templates directory
     *
     * @var string
     */
    protected $baseDir = './';
    
    /**
     * path to theme templates directory
     *
     * @var string
     */
    protected $themesDir = './';
    
    /**
     * selected theme id
     *
     * @var integer
     */
    protected $themeId = 1;
    
    /**
     * path to base resources directory
     *
     * @var string
     */
    protected $resourceBaseDir = './';
    
    /**
     * path to theme resources directory
     *
     * @var string
     */
    protected $resourceThemesDir = './';
    
    /**
     * prefix for resource references
     *
     * @var string
     */
    protected $resourcePrefix = '';
    
    /**
     * path to external base resources directory
     *
     * @var string
     */
    protected $resourceExtBaseDir = './';
    
    /**
     * path to external theme resources directory
     *
     * @var string
     */
    protected $resourceExtThemesDir = './';

    /**
     * prefix for external resource references
     *
     * @var string
     */
    protected $resourceExtPrefix = '';
    
    
    
    /**
     * line ending
     *
     * @var string
     */
    protected $EOL = PHP_EOL;
    
    


    /**
     * throw exception on error?
     *
     * @var boolean
     */
    protected $throwOnError = false;

    /**
     * render debug information into output?
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     * render pma:tile file debug information into output?
     *
     * @var boolean
     */
    protected $debugTileFilenames = true;
    
    
    
}

