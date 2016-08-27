<?php

namespace PHPagstract\Token\Tokens;

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
			"start" => "/(resource_ext:\/\/|resource:\/\/)(.*)[\"|\'|\s|\n|\ ]/iU", 
			"end" => PHP_EOL
	);

    /** @var boolean */
    public static $nested = false;
	
	/**
	 * token constructor
	 * 
	 * @param Token $parent
	 * @param string $throwOnError
	 */
    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTMARKUP, $parent, $throwOnError);

        $this->name = null;
        $this->value = null;

        $this->attributes = array();
        $this->children = array();
    }

    public function parse($html)
    {
        $html = ltrim($html);

        //echo '.pos. '.htmlentities(print_r($html, true)).' - '; flush();
        // Get token position.
        $positionArray = MarkupTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        $classname = get_class($this);
        $match = preg_match($classname::$matching["start"], $html);
        echo '<pre>'.htmlentities(print_r($match, true)).'</pre>';
        
        // Parse token.
        $posOfEndOfCData = mb_strpos($html, '}');
        //echo '.pos. '.htmlentities(print_r($positionArray, true)).' - '; flush();
        //echo '.pos. '.htmlentities(print_r($posOfEndOfCData, true)).' - '; flush();
        if ($posOfEndOfCData === false) {
            if ($this->getThrowOnError()) {
                throw new TokenizerException('Invalid Property');
            }

            return '';
        }
		$propertyReference = mb_substr($html, 2, $posOfEndOfCData-2);
        $this->value = ($propertyReference);

        return mb_substr($html, $posOfEndOfCData + 1);
    }
}
