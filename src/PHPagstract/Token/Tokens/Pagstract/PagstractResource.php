<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\ResourceTokenizer;

/**
 * PagstractSimpleValue 'pma:value' token object class
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
            "start" => "/((resource_ext:\/\/|resource:\/\/)(.*))[\"|\'|\s|\n|\ ]|((resource_ext:\/\/|resource:\/\/)(.*))$/iU", 
            "end" => PHP_EOL
    );

    /**
 * @var boolean 
*/
    public static $nested = false;
    
    /**
 * @var string 
*/
    private $type;

    /**
     * token constructor
     * 
     * @param Token  $parent
     * @param string $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTRESOURCE, $parent, $throwOnError);

        $this->type = "PagstractResource";
        $this->name = null;
        $this->value = null;

        $this->attributes = array();
        $this->children = array();
    }

    /**
     * parse for resource references
     * {@inheritDoc}
     *
     * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
     */
    public function parse($html)
    {
        $html = ltrim($html);

        // Get token position.
        $positionArray = ResourceTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        $classname = get_class($this);
        preg_match($classname::$matching["start"], $html, $match);
          
        // Parse token.
        $posOfBegin = mb_strpos($html, $match[0]);
        $length = mb_strlen($match[0]);
        $posOfEndOfCData = $posOfBegin + $length;
        
        $resourceReference = mb_substr($html, $posOfBegin, $length);
        
        if (mb_strpos($resourceReference, 'resource_ext') === false) {
            $this->name = "resource";
            $this->value = trim(trim(mb_substr($resourceReference, 11)), "\"'");
        } else {
            $this->name = "resource_ext";
            $this->value = trim(trim(mb_substr($resourceReference, 15)), "\"'");
        }

        return mb_substr($html, $posOfEndOfCData);
    }
}
