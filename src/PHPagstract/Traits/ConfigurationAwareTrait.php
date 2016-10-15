<?php
/**
 * related page configuration object awareness trait
 */
namespace PHPagstract\Traits;

use PHPagstract\Exception;
use PHPagstract\Page\Page;

/**
 * related page configuration object awareness trait
 *
 * providing setter/getter methods for page configuration
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait ConfigurationAwareTrait
{

    /**
     * related page configuration instance
     *
     * @var PHPagstract\Page\Config\PageConfig
     */
    private $configuration = null;
    
    /**
     * retrieve related page configuration instance
     * 
     * @return PHPagstract\Page\Config\PageConfig
     * @throws PHPagstract\Exception
     */
    public function getConfiguration() 
    {
        if ($this->configuration === null && $this->throwOnError === true) {
            throw new Exception("no configuration set");
        }
        return $this->configuration;
    }

    /**
     * shortcut/alias to retrieve related page configuration instance
     * 
     * @return PHPagstract\Page\Config\PageConfig
     * @see    ::getConfiguration
     */
    public function config() 
    {
        return $this->getConfiguration();
    }
    
    /**
     * set related page configuration instance
     * 
     * @param \PHPagstract\Page\Config\PageConfig $configuration
     */
    public function setConfiguration($configuration) 
    {
        $this->configuration = $configuration;
    }
}

