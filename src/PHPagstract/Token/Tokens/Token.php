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
    
    const PAGSTRACT                  = 'Pagstract';
    const PAGSTRACTMARKUP            = 'PagstractMarkup'; // any other markup than pagstract markup ('<pma:...', '<a pma:name...' etc)

    const PAGSTRACTPROPERTYREFERENCE = 'PagstractPropertyReference'; // special '${...}' handling
    const PAGSTRACTCOMMENT           = 'PagstractComment'; // special '<!--- ... -->' handling
    const PAGSTRACTRESOURCE          = 'PagstractResource'; // special 'resource(_ext)://...' handling
    const PAGSTRACTMESSAGE           = 'PagstractMessage'; // special 'msg://...' handling

    const PAGSTRACTSIMPLEVALUE       = 'PagstractSimpleValue'; // '<pma:value...'
    
    const PAGSTRACTTILE              = 'PagstractTile'; // '<pma:tile filename...'
    const PAGSTRACTTILEVARIABLE      = 'PagstractTileVariable'; // '<pma:tileVariable>{...[json-data]...}</pma:tileVariable>'
    
    const PAGSTRACTBEAN              = 'PagstractBean'; // '<pma:bean...'
    const PAGSTRACTIFVISIBLE         = 'PagstractIfVisible'; // '<pma:if-visible...'
    
    const PAGSTRACTLIST              = 'PagstractList'; // '<pma:list...'
    const PAGSTRACTLISTHEADER        = 'PagstractListHeader'; // '<pma:header...'
    const PAGSTRACTLISTFOOTER        = 'PagstractListFooter'; // '<pma:footer...'
    const PAGSTRACTLISTCONTENT       = 'PagstractListContent'; // '<pma:content...'
    const PAGSTRACTLISTNOCONTENT     = 'PagstractListNoContent'; // '<pma:no-content...'
    const PAGSTRACTLISTSEPERATOR     = 'PagstractListSeperator'; // '<pma:seperator...'
    const PAGSTRACTLISTEVEN          = 'PagstractListEven'; // '<pma:even...'
    const PAGSTRACTLISTODD           = 'PagstractListOdd'; // '<pma:odd...'
    const PAGSTRACTLISTFIRST         = 'PagstractListFirst'; // '<pma:first...'
    const PAGSTRACTLISTLAST          = 'PagstractListLast'; // '<pma:last...'
    
    const PAGSTRACTMODLIST           = 'PagstractModList'; // '<pma:modList...'
    const PAGSTRACTMODSEPERATOR      = 'PagstractModSeperator'; // '<pma:modSeperator...'
    const PAGSTRACTMODCONTENT        = 'PagstractModContent'; // '<pma:modContent...'
    
    const PAGSTRACTSWITCH            = 'PagstractSwitch'; // '<pma:switch...'
    const PAGSTRACTOBJECT            = 'PagstractObject'; // '<object pma:case...'
    
    const PAGSTRACTFORM              = 'PagstractForm'; // '<pma:form...'

    const PAGSTRACTTEXTIMG           = 'PagstractTextImg'; // '<pma:text-img...'
    
    const PAGSTRACTLINK              = 'PagstractLink'; // '<a pma:value...'
    const PAGSTRACTAREA              = 'PagstractArea'; // '<area pma:value...'
    const PAGSTRACTINPUT             = 'PagstractInput'; // '<input pma:value...'
    const PAGSTRACTSELECT            = 'PagstractSelect'; // '<select pma:name...'

    const PAGSTRACTDEBUG             = 'PagstractDebug'; // '<pma:debug...'
    
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
