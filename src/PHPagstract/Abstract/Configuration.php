<?php
/**
 * generic configuration abstract
 */
namespace PHPagstract;

/**
 * page configuration object abstract
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class AbstractConfiguration
{
    
    /**
     * initialize configuration set
     *
     * @param null|array|object $options
     */
    public function __construct( $options = null ) 
    {
        if ($options !== null ) {
            $this->setConfig($options);
        }
    }
    
    /**
     * convert if needed and set options
     *  
     * @param null|array|object $options
     */
    public function setConfig( $options = null ) 
    {
        if (is_object($options) ) {
            $options = (array)($options);
        }
        
        if (is_array($options) ) {
            foreach ($options as $key => $value) {
                $this->set($key, $value); 
            }
        }
        return $this;
    }
    
    /**
     * set a specific config key's value
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function set( $key, $value = null ) 
    {
        if (!empty($key) && is_string($key) ) {
            $this->{$key} = $value;
        }
        return $this;
    }
    
    /**
     * get a specific config key's value 
     * 
     * @param  string $key
     * @return null|mixed
     */
    public function get( $key ) 
    {
        if (property_exists($this, $key) ) {
            return $this->$key;
        }
        return null;
    }
    
    /**
     * get a specific config key's value by call to method named by key name,
     * so that...
     * $this->get('name') is equivalent to $this->name() 
     * ... and...
     * $this->set('name', 'value') is equivalent to $this->name('value') 
     * 
     * @param  string $name
     * @param  array  $arguments
     * @return null|mixed
     * @see    ::get
     */
    public function __call( $name, $arguments ) 
    {
        if (empty($arguments)  
            || !is_array($arguments)  
            || !isset($arguments[0]) 
        ) {
            return $this->get($name);
        }
        return $this->set($name, $arguments[0]);
    }
    
}

