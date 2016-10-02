<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\ResourceTokenizer;

/**
 * PagstractResource 'resource://' and 'resource_ext://' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractResource extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            //"start" => "/^((resource_ext:\/\/|resource:\/\/)(.*))[\"|\'|\s|\n|\ ]|^((resource_ext:\/\/|resource:\/\/)(.*))$/iU", 
            //"start" => "/^((resource_ext|resource):\/\/(.*))[\"|\'|\s|\n|\ ]|^((resource_ext|resource):\/\/(.*))$/iU", 
            //"start" => "/^((resource_ext|resource):\/\/(.*))[\"|\'|\s|\n|\ |".PHP_EOL."]/iU", 
            "start" => "/^((resource_ext|resource):\/\/(.*))/iU", 
            "end" => "/([\"|\'|\s|\n|\ ])/"
    );

    /**
     * @var boolean 
     */
    public $nested = false;
    
    /**
     * @var string 
     */
    private $type;

    /**
     * token constructor
     * 
     * @param Token   $parent
     * @param boolean $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTRESOURCE, $parent, $throwOnError);

        $this->type = "PagstractResource";
        
    }

    /**
     * parse for resource references
     * {@inheritDoc}
     *
     * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
     */
    public function parse($html)
    {

        // Get token position.
        $positionArray = ResourceTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);
        
        $classname = get_class($this);
        $isMatching = preg_match($classname::$matching["start"], $html, $match);
        
        if ($isMatching != 1) {
            // reg-ex is not really matching, check if we have an empty reference here...
            if ($html == 'resource://') {
                $this->name = "resource";
                $this->value = '';
                return '';
            }
            if ($html == 'resource_ext://') {
                $this->name = "resource_ext";
                $this->value = '';
                return '';
            }
            // do we have an eror here?
            // would we ever get here?
        } else {
            // reg-ex is matching, extract reference type here...
            $this->name = $match[2];
        }
        
        // Parse token.
        $posOfBegin = mb_strpos($html, $match[1]);
        $length = mb_strlen($match[0]);
        $posOfBeginOfData = $posOfBegin + $length;
        
        $remaining = mb_substr($html, $length);
        // look for the next terminating character (sequence)
        $hasEndMatch = preg_match($classname::$matching["end"], $remaining, $endMatch);
        if ($hasEndMatch === 1) {
            $posOfEndOfData = $posOfBeginOfData + mb_strpos($remaining, $endMatch[0]);
        } else {
            $posOfEndOfData = $posOfBeginOfData + mb_strlen($remaining);
        }
        
        // extract reference token value
        if ($posOfEndOfData > $posOfBeginOfData) {
            $resourceReference = mb_substr($html, $posOfBeginOfData, $posOfEndOfData - $length);
            $this->value = $resourceReference;
        } else {
            $this->value = '';
        }
        
        // return remaining content
        $remaining = mb_substr($html, $posOfEndOfData);
        
        return $remaining;
    }
}
