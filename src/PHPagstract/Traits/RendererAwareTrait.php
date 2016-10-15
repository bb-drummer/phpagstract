<?php
/**
 * renderer instance awareness trait
 */
namespace PHPagstract\Traits;

use PHPagstract\Exception;
use PHPagstract\Renderer\Renderer;

/**
 * renderer instance awareness trait
 *
 * providing setter/getter methods for (basic) renderer instance
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait RendererAwareTrait
{

    /**
     * renderer instance
     *
     * @var \PHPagstract\Renderer\Renderer
     */
    protected $renderer = null;
    
    /**
     * retrieve renderer instance
     * 
     * @return PHPagstract\Renderer\Renderer
     * @throws PHPagstract\Exception
     */
    public function getRenderer() 
    {
        if ($this->renderer === null && $this->throwOnError === true) {
            throw new Exception("no renderer instance set");
        }
        return $this->renderer;
    }

    /**
     * set renderer instance
     * 
     * @param \PHPagstract\Renderer\Renderer $renderer
     */
    public function setRenderer($renderer) 
    {
        $this->renderer = $renderer;
    }
}

