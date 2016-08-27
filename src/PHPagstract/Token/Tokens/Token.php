<?php

namespace PHPagstract\Token\Tokens;

/**
 * token interface class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
interface Token
{
    const CDATA     = 'cdata';
    const COMMENT   = 'comment';
    const DOCTYPE   = 'doctype';
    const ELEMENT   = 'element';
    const PHP       = 'php';
    const TEXT      = 'text';
    
    const CONTENIDO = 'contenido';
    
    const PAGSTRACT              = 'Pagstract';
    const PAGSTRACTSIMPLEVALUE   = 'PagstractSimpleValue';

    const PAGSTRACTVALUE         = 'PagstractValue';
    const PAGSTRACTCOMMENT       = 'PagstractComment';
    const PAGSTRACTRESOURCE      = 'PagstractResource';
    const PAGSTRACTMESSAGE       = 'PagstractMessage';
    
    const PAGSTRACTTILE          = 'PagstractTile';
    const PAGSTRACTTILEVARIABLE  = 'PagstractTileVariable';
    
    const PAGSTRACTBEAN          = 'PagstractBean';
    const PAGSTRACTIFVISIBLE     = 'PagstractIfVisible';
    
    const PAGSTRACTLIST          = 'PagstractList';
    const PAGSTRACTLISTHEADER    = 'PagstractListHeader';
    const PAGSTRACTLISTFOOTER    = 'PagstractListFooter';
    const PAGSTRACTLISTCONTENT   = 'PagstractListVontent';
    const PAGSTRACTLISTNOCONTENT = 'PagstractListNoContent';
    const PAGSTRACTLISTSEPERATOR = 'PagstractListSeperator';
    const PAGSTRACTLISTEVEN      = 'PagstractListEven';
    const PAGSTRACTLISTODD       = 'PagstractListOdd';
    const PAGSTRACTLISTFIRST     = 'PagstractListFirst';
    const PAGSTRACTLISTLAST      = 'PagstractListLast';
    
    const PAGSTRACTMODLIST       = 'PagstractModlist';
    const PAGSTRACTMODSEPERATOR  = 'PagstractModseperator';
    const PAGSTRACTMODCONTENT    = 'PagstractModcontent';
    
    const PAGSTRACTSWITCH        = 'PagstractSwitch';
    const PAGSTRACTOBJECT        = 'PagstractObject';
    const PAGSTRACTFORM          = 'PagstractForm';

    const PAGSTRACTTESTIMG       = 'PagstractTextImg';
    
    const PAGSTRACTLINK          = 'PagstractLink';
    const PAGSTRACTAREA          = 'PagstractArea';
    const PAGSTRACTINPUT         = 'PagstractInput';
    const PAGSTRACTSELECT        = 'PagstractSelect';

    const PAGSTRACTDEBUG         = 'PagstractDebug';
    
    /**
     * Will return the nesting depth of the token.
     *
     * @return int
     */
    public function getDepth();

    /**
     * Will return the token line number.
     *
     * @return int
     */
    public function getLine();

    /**
     * Will return the token line position.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Will return true of the parent should be closed automatically.
     *
     * @param string $html
     *
     * @return boolean
     */
    public function isClosingElementImplied($html);

    /**
     * Will parse this token.
     *
     * @param string $html
     *
     * @return string Remaining HTML.
     */
    public function parse($html);

    /**
     * Will return the parent token or null if none.
     *
     * @return Token|null
     */
    public function getParent();

    /**
     * Will return the type of token.
     *
     * @return string
     */
    public function getType();

    public function isCDATA();
    public function isComment();
    public function isDocType();
    public function isElement();
    public function isPhp();
    public function isText();

    /**
     * Will convert this token to an array structure.
     *
     * @return array
     */
    public function toArray();
}
