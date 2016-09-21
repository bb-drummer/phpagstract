<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\Token;
use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\PagstractTokenizer;
use PHPagstract\Token\MarkupTokenizer;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
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
        $result = TokenFactory::buildFromHtml($html);
        $this->assertInstanceOf($expectedClassName, $result);
        
    }

    /**
     * @dataProvider buildFromHtmlPagstractDataProvider
     */
    public function testBuildFromHtmlPagstract($html, $expectedClassName)
    {
        // just to be sure, init Pagstract tokenizer setup
        new PagstractTokenizer();
        $result = TokenFactory::buildFromHtml($html);
        $this->assertInstanceOf($expectedClassName, $result);
        
    }

    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtmlThrowsException($html, $expectedClassName)
    {
        
        TokenFactory::$matchings = array("UnknownToken" => array(
                "start" => '/^[^<]/',
                "end" => PHP_EOL,
        ));
        try {
            $result = TokenFactory::buildFromHtml($html, null, true);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
        }
        
    }

    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtmlReturnsFalse($html, $expectedClassName)
    {
        
        TokenFactory::$matchings = array("UnknownToken" => array(
                "start" => '/^[^<]/',
                "end" => PHP_EOL,
        ));
        $result = TokenFactory::buildFromHtml($html);
        $this->assertFalse($result);
        
    }

    public function testRegisterMatchingExceptionRegisteredAlready() 
    {
        
        TokenFactory::registerMatching("Text", "/.*/", PHP_EOL);
        try {
            TokenFactory::registerMatching("Text", "/.*/", PHP_EOL);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('Token has been registered already', $e->getMessage());
        }
        
    }

    public function testRegisterMatchingExceptionInvalidTokenName() 
    {
        
        try {
            TokenFactory::registerMatching("", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('Invalid token classname given', $e->getMessage());
        }

    }
        
    public function testRegisterMatchingExceptionNoTokenClass() 
    {
             
        try {
            TokenFactory::registerMatching("AnotherUnknownToken", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token class found', $e->getMessage());
        }
        
    }
        
    public function testRegisterMatchingExceptionNoMatchingFound() 
    {
             
        try {
            TokenFactory::registerMatching("\StdClass", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No matching found', $e->getMessage());
        }
        
    }

    public function testRegisterMatchingExceptionNoTokenEndSequence() 
    {
             
        try {
            TokenFactory::registerMatching("\StdClass", "/.*/i", "");
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token-end sequence given', $e->getMessage());
        }
        
    }

    public function testRegisterMatching() 
    {
        
        TokenFactory::clearMatchings();
        TokenFactory::registerMatching("Text", "/.*/i", PHP_EOL);
        $matchings = TokenFactory::getMatchings();

        $this->assertArrayHasKey("Text", $matchings);
        $this->assertArrayHasKey("start", $matchings["Text"]);
        $this->assertArrayHasKey("end", $matchings["Text"]);
        
    }

    public function testGetMatchingFromTokenClassExceptionNoTokenClass() 
    {
             
        try {
            $matching = TokenFactory::getTokenMatchingFromClass("UnknownToken");
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token class found', $e->getMessage());
        }
        
    }
        
    public function buildFromHtmlDataProvider()
    {
        return array(
            "default text" => array(
                'asdf',
                'PHPagstract\Token\Tokens\Text'
            ),
            "markup tag" => array(
                '<asdf></asdf>',
                'PHPagstract\Token\Tokens\Element'
            ),
            "markup tag (self-closed)" => array(
                '<asdf />',
                'PHPagstract\Token\Tokens\Element'
            ),
            "default comment" => array(
                '<!-- asdf -->',
                'PHPagstract\Token\Tokens\Comment'
            ),
            "CDATA" => array(
                '<![CDATA[asdf]]>',
                'PHPagstract\Token\Tokens\CData'
            ),
            "DOCTYPE" => array(
                '<!DOCTYPE asdf >',
                'PHPagstract\Token\Tokens\DocType'
            ),
            "PHP" => array(
                '<?php asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            "PHP (short)" => array(
                '<? asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            )
        );
    }

    public function buildFromHtmlPagstractDataProvider()
    {
        return array(
            "default text" => array(
                'asdf',
                'PHPagstract\Token\Tokens\Text'
            ),
            "default comment" => array(
                '<!-- asdf -->',
                'PHPagstract\Token\Tokens\Comment'
            ),
            "CDATA" => array(
                '<![CDATA[asdf]]>',
                'PHPagstract\Token\Tokens\CData'
            ),
            "DOCTYPE" => array(
                '<!DOCTYPE asdf >',
                'PHPagstract\Token\Tokens\DocType'
            ),
            "PHP" => array(
                '<?php asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            "PHP (short)" => array(
                '<? asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            "markup tag (self-closed)" => array(
                '<asdf />',
                'PHPagstract\Token\Tokens\PagstractMarkup'
            ),
            "markup tag" => array(
                '<asdf></asdf>',
                'PHPagstract\Token\Tokens\PagstractMarkup'
            ),
                
                
            "pma:value" => array(
                '<pma:value pma:name=".property"></pma:value>',
                'PHPagstract\Token\Tokens\PagstractSimpleValue'
            ),
            "pma:value (self-closed)" => array(
                '<pma:value pma:name=".property" />',
                'PHPagstract\Token\Tokens\PagstractSimpleValue'
            ),

            
            "pagstract comment" => array(
                '<!--- pagstract comment -->',
                'PHPagstract\Token\Tokens\PagstractComment'
            ),
            "pma:debug" => array(
                '<pma:debug pma:name />',
                'PHPagstract\Token\Tokens\PagstractDebug'
            ),    
            "pma:bean" => array(
                '<pma:bean pma:name></pma:bean>',
                'PHPagstract\Token\Tokens\PagstractBean'
            ),
            "pma:if-visible" => array(
                '<pma:if-visible pma:name=".property"></pma:if-visible>',
                'PHPagstract\Token\Tokens\PagstractIfVisible'
            ),
                
                
            "pma:list" => array(
                '<pma:list pma:name=".listproperty">Link</pma:list>',
                'PHPagstract\Token\Tokens\PagstractList'
            ),    
            "pma:header" => array(
                '<pma:header>some content</pma:header>',
                'PHPagstract\Token\Tokens\PagstractListHeader'
            ),    
            "pma:content" => array(
                '<pma:content>some content</pma:content>',
                'PHPagstract\Token\Tokens\PagstractListContent'
            ),    
            "pma:footer" => array(
                '<pma:footer>some content</pma:footer>',
                'PHPagstract\Token\Tokens\PagstractListFooter'
            ),    
            "pma:seperator" => array(
                '<pma:seperator>some content</pma:seperator>',
                'PHPagstract\Token\Tokens\PagstractListSeperator'
            ),    
            "pma:first" => array(
                '<pma:first>some content</pma:first>',
                'PHPagstract\Token\Tokens\PagstractListFirst'
            ),    
            "pma:last" => array(
                '<pma:last>some content</pma:last>',
                'PHPagstract\Token\Tokens\PagstractListLast'
            ),    
            "pma:even" => array(
                '<pma:even>some content</pma:even>',
                'PHPagstract\Token\Tokens\PagstractListEven'
            ),    
            "pma:odd" => array(
                '<pma:odd>some content</pma:odd>',
                'PHPagstract\Token\Tokens\PagstractListOdd'
            ),    
            "pma:no-content" => array(
                '<pma:no-content>some content</pma:no-content>',
                'PHPagstract\Token\Tokens\PagstractListNoContent'
            ),    
            "pma:nocontent" => array(
                '<pma:nocontent>some content</pma:nocontent>',
                'PHPagstract\Token\Tokens\PagstractListNoContent'
            ),    
                
                
            "pma:modList" => array(
                '<pma:modList pma:name>some content</pma:modList>',
                'PHPagstract\Token\Tokens\PagstractModList'
            ),    
            "pma:modContent" => array(
                '<pma:modContent>some content</pma:modContent>',
                'PHPagstract\Token\Tokens\PagstractModContent'
            ),    
            "pma:modSeperator" => array(
                '<pma:modSeperator>some content</pma:modSeperator>',
                'PHPagstract\Token\Tokens\PagstractModSeperator'
            ),
                
                
            "pma:form" => array(
                '<pma:form pma:name=".formproperty"></pma:form>',
                'PHPagstract\Token\Tokens\PagstractForm'
            ),    
                
                
            "pma:switch" => array(
                '<pma:switch pma:name>some content</pma:switch>',
                'PHPagstract\Token\Tokens\PagstractSwitch'
            ),    
            "pagstract switch object" => array(
                '<object pma:case>some content</object>',
                'PHPagstract\Token\Tokens\PagstractObject'
            ),    

                
            "pma:tile" => array(
                '<pma:tile filename="./partial.html"></pma:tile>',
                'PHPagstract\Token\Tokens\PagstractTile'
            ),
            "pma:tileVariable" => array(
                '<pma:tileVariable>{"some" : "json"}</pma:tileVariable>',
                'PHPagstract\Token\Tokens\PagstractTileVariable'
            ),
                
                
            "pagstract link" => array(
                '<a pma:name=".actionproperty">Link</a>',
                'PHPagstract\Token\Tokens\PagstractLink'
            ),
            "pagstract area" => array(
                '<area pma:name />',
                'PHPagstract\Token\Tokens\PagstractArea'
            ),
            "pagstract input" => array(
                '<input pma:name=".inputproperty" />',
                'PHPagstract\Token\Tokens\PagstractInput'
            ),
            "pagstract select (self-closed)" => array(
                '<select pma:name=".selectproperty" />',
                'PHPagstract\Token\Tokens\PagstractSelect'
            ),
            "pagstract select" => array(
                '<select pma:name=".selectproperty" multiple></select>',
                'PHPagstract\Token\Tokens\PagstractSelect'
            ),
                
        );
    }

    public function testNoTypeFoundReturnsFalseInBuildFromHtml()
    {
        
        TokenFactory::clearMatchings();
        
        $this->assertFalse(TokenFactory::buildFromHtml('< asdfasdf'));
        
    }

    /**
     * @expectedException PHPagstract\Token\Exception\TokenFactoryException
     */
    public function testNoTypeFoundThrowsExceptionInBuildFromHtml()
    {
        
        TokenFactory::buildFromHtml('< asdfasdf', null, true);
        
    }
    
}
