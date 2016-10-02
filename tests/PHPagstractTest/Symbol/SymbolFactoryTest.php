<?php
/**
 * PHPagstract symbol factory class tests
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
namespace PHPagstractTest\Symbol;

use PHPagstract\Token\Tokens\Token;
use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Symbol\Symbols\SymbolFactory;
use PHPagstract\Token\ResourceTokenizer;
use PHPagstract\Token\MessageTokenizer;
use PHPagstract\Token\PropertyReferenceTokenizer;

class SymbolFactoryTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        TokenFactory::clearMatchings();
    }
    
    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtml($html, $expectedClassName)
    {
        // just to be sure, init default/HTML tokenizer setup
        new MarkupTokenizer();
        $token = TokenFactory::buildFromHtml($html);
        $symbol = SymbolFactory::symbolize($token);
        $this->assertInstanceOf($expectedClassName, $symbol);
        
    }

    /**
     * @dataProvider buildFromHtmlPagstractDataProvider
     */
    public function testBuildFromHtmlPagstract($html, $expectedClassName)
    {
        // just to be sure, init Pagstract tokenizer setup
        new PagstractTokenizer();
        $token = TokenFactory::buildFromHtml($html);
        $symbol = SymbolFactory::symbolize($token);
        $this->assertInstanceOf($expectedClassName, $symbol);
        
    }
    
    /**
     * @dataProvider buildFromHtmlPropertyReferenceDataProvider
     */
    public function testBuildFromHtmlPropertyReference($html, $expectedClassName)
    {
        // just to be sure, init Pagstract tokenizer setup
        new PropertyReferenceTokenizer();
        $token = TokenFactory::buildFromHtml($html);
        $symbol = SymbolFactory::symbolize($token);
        $this->assertInstanceOf($expectedClassName, $symbol);
        
    }
    
    /**
     * @dataProvider buildFromHtmlResourceDataProvider
     */
    public function testBuildFromHtmlResource($html, $expectedClassName)
    {
        // just to be sure, init Pagstract tokenizer setup
        new ResourceTokenizer();
        $token = TokenFactory::buildFromHtml($html);
        $symbol = SymbolFactory::symbolize($token);
        $this->assertInstanceOf($expectedClassName, $symbol);
        
    }
    
    /**
     * @dataProvider buildFromHtmlMessageDataProvider
     */
    public function testBuildFromHtmlMessage($html, $expectedClassName)
    {
        // just to be sure, init Pagstract tokenizer setup
        new MessageTokenizer();
        $token = TokenFactory::buildFromHtml($html);
        $symbol = SymbolFactory::symbolize($token);
        $this->assertInstanceOf($expectedClassName, $symbol);
        
    }
    
    public function buildFromHtmlPropertyReferenceDataProvider()
    {
        return array(
                "default text" => array(
                        'asdf',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractPropertyReferenceText'
                ),
                "a property reference" => array(
                        '${proptery}',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractPropertyReference'
                ),
        );
    }
    
    public function buildFromHtmlResourceDataProvider()
    {
        return array(
                "default text" => array(
                        'asdf',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\Text'
                ),
                "a resource reference" => array(
                        'resource://',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractResource'
                ),
        );
    }
    
    public function buildFromHtmlMessageDataProvider()
    {
        return array(
                "default text" => array(
                        'asdf',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\Text'
                ),
                "a message reference" => array(
                        'msg://',
                        'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractMessage'
                ),
        );
    }
    
    public function buildFromHtmlDataProvider()
    {
        return array(
            "default text" => array(
                'asdf',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Text'
            ),
            "markup tag" => array(
                '<asdf></asdf>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Element'
            ),
            "markup tag (self-closed)" => array(
                '<asdf />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Element'
            ),
            "nested markup tags" => array(
                '<asdf><asdf></asdf></asdf>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Element'
            ),
            "default comment" => array(
                '<!-- asdf -->',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Comment'
            ),
            "CDATA" => array(
                '<![CDATA[asdf]]>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\CData'
            ),
            "DOCTYPE" => array(
                '<!DOCTYPE asdf >',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\DocType'
            ),
            "PHP" => array(
                '<?php asdf; ?>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Php'
            ),
            "PHP (short)" => array(
                '<? asdf; ?>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Php'
            )
        );
    }

    public function buildFromHtmlPagstractDataProvider()
    {
        return array(
            "default text" => array(
                'asdf',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Text'
            ),
            "default comment" => array(
                '<!-- asdf -->',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Comment'
            ),
            "CDATA" => array(
                '<![CDATA[asdf]]>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\CData'
            ),
            "DOCTYPE" => array(
                '<!DOCTYPE asdf >',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\DocType'
            ),
            "PHP" => array(
                '<?php asdf; ?>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Php'
            ),
            "PHP (short)" => array(
                '<? asdf; ?>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\Php'
            ),
                
            "markup tag (self-closed)" => array(
                '<asdf />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractMarkup'
            ),
            "markup tag" => array(
                '<asdf></asdf>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractMarkup'
            ),
                
                
            "pma:value" => array(
                '<pma:value pma:name=".property"></pma:value>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractSimpleValue'
            ),
            "pma:value (self-closed)" => array(
                '<pma:value pma:name=".property" />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractSimpleValue'
            ),

            
            "pagstract comment" => array(
                '<!--- pagstract comment -->',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractComment'
            ),
            "pma:debug" => array(
                '<pma:debug pma:name />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractDebug'
            ),    
            "pma:bean" => array(
                '<pma:bean pma:name></pma:bean>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractBean'
            ),
            "pma:if-visible" => array(
                '<pma:if-visible pma:name=".property"></pma:if-visible>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractIfVisible'
            ),
            "nested pma:if-visible" => array(
                '<pma:if-visible pma:name=".property"><asdf></asdf></pma:if-visible>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractIfVisible'
            ),
                
                
            "pma:list" => array(
                '<pma:list pma:name=".listproperty">Link</pma:list>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractList'
            ),    
            "pma:header" => array(
                '<pma:header>some content</pma:header>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListHeader'
            ),    
            "pma:content" => array(
                '<pma:content>some content</pma:content>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListContent'
            ),    
            "pma:footer" => array(
                '<pma:footer>some content</pma:footer>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListFooter'
            ),    
            "pma:seperator" => array(
                '<pma:seperator>some content</pma:seperator>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListSeperator'
            ),    
            "pma:first" => array(
                '<pma:first>some content</pma:first>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListFirst'
            ),    
            "pma:last" => array(
                '<pma:last>some content</pma:last>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListLast'
            ),    
            "pma:even" => array(
                '<pma:even>some content</pma:even>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListEven'
            ),    
            "pma:odd" => array(
                '<pma:odd>some content</pma:odd>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListOdd'
            ),    
            "pma:no-content" => array(
                '<pma:no-content>some content</pma:no-content>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListNoContent'
            ),    
            "pma:nocontent" => array(
                '<pma:nocontent>some content</pma:nocontent>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractListNoContent'
            ),    
                
                
            "pma:modList" => array(
                '<pma:modList pma:name>some content</pma:modList>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractModList'
            ),    
            "pma:modContent" => array(
                '<pma:modContent>some content</pma:modContent>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractModContent'
            ),    
            "pma:modSeperator" => array(
                '<pma:modSeperator>some content</pma:modSeperator>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractModSeperator'
            ),
                
                
            "pma:form" => array(
                '<pma:form pma:name=".formproperty"></pma:form>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractForm'
            ),    
                
                
            "pma:switch" => array(
                '<pma:switch pma:name>some content</pma:switch>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractSwitch'
            ),    
            "pagstract switch object" => array(
                '<object pma:case>some content</object>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractObject'
            ),    

                
            "pma:tile" => array(
                '<pma:tile filename="./partial.html"></pma:tile>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractTile'
            ),
            "pma:tileVariable" => array(
                '<pma:tileVariable>{"some" : "json"}</pma:tileVariable>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractTileVariable'
            ),
                
                
            "pagstract link" => array(
                '<a pma:name=".actionproperty">Link</a>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractLink'
            ),
            "pagstract area" => array(
                '<area pma:name />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractArea'
            ),
            "pagstract input" => array(
                '<input pma:name=".inputproperty" />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractInput'
            ),
            "pagstract select (self-closed)" => array(
                '<select pma:name=".selectproperty" />',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractSelect'
            ),
            "pagstract select" => array(
                '<select pma:name=".selectproperty" multiple></select>',
                'PHPagstract\\Symbol\\Symbols\\Tokens\\PagstractSelect'
            ),
                
        );
    }

}
