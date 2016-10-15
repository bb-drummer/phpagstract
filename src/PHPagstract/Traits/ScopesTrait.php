<?php

namespace PHPagstract\Traits;

/**
 * (property resolving) scopes handling trait
 * 
 * providing setter/getter methods for handling (property resolving) scopes
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
trait ScopesTrait
{

    /**
     * sequence of scopes (aka 'bean's)
     *
     * @var array
     */
    private $scopes = [];

    /**
     * add a new scope to scope sequence
     * called when encountering a opening 'pma:bean' tag
     * 
     * @param mixed $scope
     */
    public function addScope($scope)
    {
        $this->scopes[] = $scope;
        return $this;
    }
    
    /**
     * retrieve the scope sequence
     * 
     * @return array $scopes
     */
    public function getScopes() 
    {
        return $this->scopes;
    }
    
    /**
     * retrieve the first scope from scope sequence
     * 
     * @return mixed $scopes[0]
     */
    public function getRootScope() 
    {
        if (!isset($this->scopes[0])) {
            return null;
        }
        return $this->scopes[0];
    }
    
    /**
     * set a new sequence of scopes
     * 
     * @param array $scopes
     */
    public function setScopes($scopes) 
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * reset/clear the sequence of scopes
     */
    public function resetScopes() 
    {
        $this->scopes = [];
        return $this;
    }

    /**
     * removes the last scope from scope sequence
     * called when encountering a closing 'pma:bean' tag
     */
    public function unsetLastScope() 
    {
        $scopeCount = count($this->getScopes());
        if ($scopeCount > 1) {
            unset($this->scopes[$scopeCount - 1]);
        }
        return $this;
    }
}

