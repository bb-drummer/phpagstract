<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MessageTokenizer;

/**
 * Pagstract message reference 'msg://...' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractMessage extends PagstractAbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
            "start" => "/^((msg:\/\/)(.*))(?![\w\b\.\-\_])/iU",
            "end" => PHP_EOL
    );
            
    /**
     * @var boolean 
     */
    public $nested = false;

    /**
     * token constructor
     * 
     * @param Token  $parent
     * @param boolean $throwOnError
     */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTMESSAGE, $parent, $throwOnError);
        
    }

    /**
     * parse for message references
     * {@inheritDoc}
     *
     * @see \PHPagstract\Token\Tokens\PagstractAbstractToken::parse()
     */
    public function parse($html)
    {
        $html = ltrim($html);
    
        // Get token position.
        $positionArray = MessageTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);
    
        $classname = get_class($this);
        preg_match($classname::$matching["start"], $html, $match);

        // Parse token.
        $posOfBegin = mb_strpos($html, $match[0]);
        $length = mb_strlen($match[0]);
        $posOfEndOfCData = $posOfBegin + $length;
        
        $messageReference = mb_substr($html, $posOfBegin, $length);
        
        $this->name = 'msg';
        $this->value = trim(trim(mb_substr($messageReference, 6)), "\"'/,!§$%&/()=?´`+*#:;^°<>");
    
        return mb_substr($html, $posOfEndOfCData);
    }
}
