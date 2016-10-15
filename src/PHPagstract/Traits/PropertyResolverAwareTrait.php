<?php
/**
 * property reference resolver awareness
 */
namespace PHPagstract\Traits;

use PHPagstract\Page\Resolver\PropertyResolver;

/**
 * property refrence resolver awareness trait
 *
 * providing setter/getter methods for (basic) page building configuration
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait PropertyResolverAwareTrait
{
    
    /**
     * property reference resolver
     *
     * @var PropertyResolver
     */
    public $propertyResolver = null;

    /**
     * retrieve a property resolver instance
     * if not set, initialize new instance
     *
     * @return \PHPagstract\Page\Resolver\PropertyResolver $propertyResolver
     */
    public function getPropertyResolver() 
    {
        if (!($this->propertyResolver instanceof PropertyResolver)) {
            $this->propertyResolver = new PropertyResolver(null, $this->throwOnError);
            if (method_exists($this, 'getDataStream')) {
                $this->propertyResolver->setStream($this->getDataStream());
                $this->propertyResolver->addScope($this->getDataStream());
            }
        }
        return $this->propertyResolver;
    }

    /**
     * set the property resolver instance
     * 
     * @param \PHPagstract\Page\Resolver\PropertyResolver $propertyResolver
     */
    public function setPropertyResolver($propertyResolver) 
    {
        $this->propertyResolver = $propertyResolver;
    }
    
}

