<?php

namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Token\Exception\TokenizerException;

/**
 * 'CData' token object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractPropertyReference extends PagstractAbstractToken
{
	/**
	 * @var array the $matching
	 */
	public static $matching = array(
			"start" => "/([$][{])/i", 
			"end" => "}"
	);

    /** @var boolean */
    public static $nested = false;

    public function __construct(Token $parent = null, $throwOnError = false)
    {
        parent::__construct(Token::PAGSTRACTPROPERTYREFERENCE, null, $throwOnError);

        $this->value = null;
    }

    public function parse($html)
    {
        $html = ltrim($html);

        // Get token position.
        $positionArray = MarkupTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        // Parse token.
        $posOfEndOfCData = mb_strpos($html, '}');
        if ($posOfEndOfCData === false) {
            if ($this->getThrowOnError()) {
                throw new TokenizerException('Invalid Property');
            }

            return '';
        }
		$propertyReference = mb_substr($html, 2, $posOfEndOfCData-2);
        
        $this->name = ($propertyReference);
        $this->value = ($propertyReference);

        return mb_substr($html, $posOfEndOfCData + 1);
    }
}
