<?php

namespace PHPagstract\Token;

use PHPagstract\Token\Tokens\TokenCollection;
use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\Tokens\Element;

/**
 * markup tokenizer object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class MarkupTokenizer
{
    /** @var boolean */
    private $throwOnError;

    /** @var string */
    private static $allHtml = '';

    /**
     * Constructor
     */
    public function __construct($throwOnError = false)
    {
        $this->throwOnError = (boolean) $throwOnError;

        Element::$nested = true;
        TokenFactory::clearMatchings();
        TokenFactory::registerMatching("Php");
        TokenFactory::registerMatching("Comment");
        TokenFactory::registerMatching("CData");
        TokenFactory::registerMatching("DocType");
        TokenFactory::registerMatching("Element");
        TokenFactory::registerMatching("Text");
    }

    /**
     * Will parse html into tokens.
     *
     * @param $html string The HTML to tokenize.
     *
     * @return TokenCollection
     */
    public function parse($html)
    {
        self::$allHtml = $html;
        $tokens = new TokenCollection();
        $remainingHtml = trim((string) $html);
        while (mb_strlen($remainingHtml) > 0) {
            $token = TokenFactory::buildFromHtml(
                $remainingHtml,
                null,
                $this->throwOnError
            );
            if ($token === false) {
                // Error has occurred, so we stop.
                break;
            }

            $remainingHtml = $token->parse($remainingHtml);
            $tokens[] = $token;
        }

        return $tokens;
    }

    public static function getPosition($partialHtml)
    {
        $position = mb_strrpos(self::$allHtml, $partialHtml);
        $parsedHtml = mb_substr(self::$allHtml, 0, $position);
        $line = mb_substr_count($parsedHtml, "\n");
        if ($line === 0) {
            return array(
                'line' => 0,
                'position' => $position
            );
        }

        $lastNewLinePosition = mb_strrpos($parsedHtml, "\n");

        return array(
            'line' => $line,
            'position' => mb_strlen(mb_substr($parsedHtml, $lastNewLinePosition))
        );
    }
}
