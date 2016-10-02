<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\Exception\TokenFactoryException;
use PHPagstract\Token\Tokens\Token;

/**
 * token factory object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class TokenFactory
{
    /**
     * the matching registry
     *
     * @var array
     */
    public static $matchings = array();
    
    /**
     * build token (tree) from string
     * 
     * @param  string  $html
     * @param  Token   $parent
     * @param  boolean $throwOnError
     * @throws TokenFactoryException
     * @return boolean|Token
     */
    public static function buildFromHtml($html, Token $parent = null, $throwOnError = false)
    {
        $matchCriteria = self::getMatchings();
        foreach ($matchCriteria as $className => $regex) {
            if (preg_match($regex["start"], $html) === 1) {
                $fullClassName = $className;
                if (!class_exists($className)) {
                    $fullClassName = "PHPagstract\\Token\\Tokens\\".$className;
                    if (!class_exists($fullClassName)) {
                        if ($throwOnError) {
                            throw new TokenFactoryException("No token class found for '.$className.'");
                        } else {
                            return false;
                        }
                    }
                }
                $fullClassName::$matching = $regex;
                return new $fullClassName($parent, $throwOnError);
            } 
        }

        // Error condition
        if ($throwOnError) {
            throw new TokenFactoryException("Could not resolve token");
        }

        return false;
    }
    
    /**
     * @return array the $matchings
     */
    public static function getMatchings() 
    {
        return self::$matchings;
    }

    /**
     * @param string $className
     * @return array|boolean the token's $matching
     */
    public static function getTokenMatchingFromClass($className) 
    {
        $fullClassName = $className;
        if (!class_exists($className)) {
            $fullClassName = "PHPagstract\\Token\\Tokens\\".$className;
            if (!class_exists($fullClassName)) {
                throw new TokenFactoryException("No token class found for '.$className.'");
            }
        }
        if (property_exists($fullClassName, "matching")) {
            return $fullClassName::$matching;
        }
        return false;
    }

    /**
     * remove all tokens from registry
     */
    public static function clearMatchings() 
    {
        self::$matchings = array();
    }

    /**
     * add new token/matching to registry
     * 
     * @param  string $className
     * @param  string $regexStart
     * @param  string $regexEnd
     * @return self
     */
    public static function registerMatching($className, $regexStart = null, $regexEnd = null) 
    {
        if (array_key_exists($className, self::$matchings)) {
            throw new TokenFactoryException("Token has been registered already");
        }
        if (!is_string($className) || empty($className)) {
            throw new TokenFactoryException("Invalid token classname given");
        } else if (!self::tokenExists($className)) {
            throw new TokenFactoryException("No token class found for '".$className."'");
        }
        if (!is_string($regexStart) || empty($regexStart)) {
            $matching = self::getTokenMatchingFromClass($className);
            if (!is_array($matching)) {
                throw new TokenFactoryException("No matching found in token class '".$className."'");
            }

            self::$matchings[$className] = array(
                    "start" => $matching["start"],
                    "end"   => $matching["end"],
            );
            return;
        }
        
        if (!is_string($regexEnd) || empty($regexEnd)) {
            throw new TokenFactoryException("No token-end sequence given");
        }
        self::$matchings[$className] = array(
            "start" => $regexStart,
            "end"   => $regexEnd,
        );
            
        return;
        
    }

    /**
     * @param string $className
     * @return boolean the token of $classname exists?
     */
    public static function tokenExists($className) 
    {
        if (!class_exists($className)) {
            $fullClassName = "PHPagstract\\Token\\Tokens\\".$className;
            if (!class_exists($fullClassName)) {
                return false;
            }
        }
    
        return true;
    }
    
    
}
