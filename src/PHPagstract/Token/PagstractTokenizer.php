<?php

namespace PHPagstract\Token;

use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\Tokens\Token;
use PHPagstract\Token\Tokens\PagstractMarkup;

/**
 * markup tokenizer object class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractTokenizer extends MarkupTokenizer
{
    /**
     * @var boolean 
     */
    protected $throwOnError;

    /**
     * @var string 
     */
    protected static $allHtml = '';

    /**
     * Constructor
     */
    public function __construct($throwOnError = false)
    {
        $this->throwOnError = (boolean) $throwOnError;
        
        TokenFactory::clearMatchings();

        TokenFactory::registerMatching("Php");
        TokenFactory::registerMatching("CData");
        TokenFactory::registerMatching("DocType");
        
        TokenFactory::registerMatching(Token::PAGSTRACTCOMMENT);
        TokenFactory::registerMatching("Comment");
        TokenFactory::registerMatching(Token::PAGSTRACTSIMPLEVALUE);
        
        TokenFactory::registerMatching(Token::PAGSTRACTTILE);
        TokenFactory::registerMatching(Token::PAGSTRACTTILEVARIABLE);
        
        TokenFactory::registerMatching(Token::PAGSTRACTBEAN);
        TokenFactory::registerMatching(Token::PAGSTRACTIFVISIBLE);
        
        TokenFactory::registerMatching(Token::PAGSTRACTLIST);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTHEADER);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTCONTENT);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTFOOTER);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTSEPERATOR);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTFIRST);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTLAST);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTEVEN);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTODD);
        TokenFactory::registerMatching(Token::PAGSTRACTLISTNOCONTENT);
        
        TokenFactory::registerMatching(Token::PAGSTRACTMODLIST);
        TokenFactory::registerMatching(Token::PAGSTRACTMODCONTENT);
        TokenFactory::registerMatching(Token::PAGSTRACTMODSEPERATOR);

        TokenFactory::registerMatching(Token::PAGSTRACTSWITCH);
        TokenFactory::registerMatching(Token::PAGSTRACTOBJECT);
        
        TokenFactory::registerMatching(Token::PAGSTRACTFORM);
        
        // this is currently not available: TokenFactory::registerMatching(Token::PAGSTRACTTEXTIMG);

        TokenFactory::registerMatching(Token::PAGSTRACTLINK);
        TokenFactory::registerMatching(Token::PAGSTRACTAREA);
        TokenFactory::registerMatching(Token::PAGSTRACTINPUT);
        TokenFactory::registerMatching(Token::PAGSTRACTSELECT);
        
        TokenFactory::registerMatching(Token::PAGSTRACTDEBUG);
        
        TokenFactory::registerMatching(Token::PAGSTRACTMARKUP);

        TokenFactory::registerMatching("Text");
    }

}
