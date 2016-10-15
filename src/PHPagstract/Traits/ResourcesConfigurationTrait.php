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
trait ResourcesConfigurationTrait
{
    
    /**
     * resources' path replacement
     *
     * @var string
     */
    public $resourcesPath = "./";
    
    /**
     * extenal resources' path replacement
     *
     * @var string
     */
    public $resourcesExtPath = "./";
    
    
    
    /**
     * @return string $resourcesPath
     */
    public function getResourcesPath() 
    {
        return $this->resourcesPath;
    }
    
    /**
     * @param string $resourcesPath
     */
    public function setResourcesPath($resourcesPath) 
    {
        $this->resourcesPath = $resourcesPath;
    }
    
    /**
     * @return string $resourcesExtPath
     */
    public function getResourcesExtPath() 
    {
        return $this->resourcesExtPath;
    }
    
    /**
     * @param string $resourcesExtPath
     */
    public function setResourcesExtPath($resourcesExtPath) 
    {
        $this->resourcesExtPath = $resourcesExtPath;
    }
    
}

