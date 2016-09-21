<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\ResourceTokenizer;

/**
 * 'Text' token object class, aka. everything that is not a property reference
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractResourceText extends PagstractTextAbstract
{
    /**
     * token constructor
     * 
     * @param Token  $parent
     * @param string $throwOnError
     * @param string $forcedValue
     */
    public function __construct(Token $parent = null, $throwOnError = false, $forcedValue = null)
    {
        parent::__construct(Token::PAGSTRACTRESOURCETEXT, $parent, $throwOnError);

        $this->value = $forcedValue;
    }

	
    /**
     * parse for everything that is not a property reference
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

        // determine next reference token pos, if there is one
        $posOfNextResourceExt = mb_strpos($html, 'resource_ext://');
        $posOfNextResource = mb_strpos($html, 'resource://');
        if (($posOfNextResource === false) && ($posOfNextResourceExt === false)) {
        	// no next token, return remaining content
            $this->value = $html;
            return '';
        } else if (($posOfNextResource === false) && ($posOfNextResourceExt !== false)) {
        	// one next token found ('ext')
            $posOfNextElement = $posOfNextResourceExt;
        } else if (($posOfNextResource !== false) && ($posOfNextResourceExt === false)) {
        	// one next token found (default)
            $posOfNextElement = $posOfNextResource;
        } else {
        	// both next token found ('ext' or default)
        	$posOfNextElement = ($posOfNextResourceExt < $posOfNextResource) ? $posOfNextResourceExt : $posOfNextResource;
        }

        // extract text content
        $text = mb_substr($html, 0, $posOfNextElement);
        $this->value = $text;
        
        // return remaining content
		$remaining = mb_substr($html, mb_strlen($text));
        return $remaining; 
    }

}

