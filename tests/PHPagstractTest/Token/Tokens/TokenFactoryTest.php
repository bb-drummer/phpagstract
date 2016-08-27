<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens;
use PHPagstract\Token\Tokens\TokenFactory;
use PHPagstract\Token\MarkupTokenizer;
use PHPagstract\Token\PagstractTokenizer;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtml($html, $expectedClassName)
    {
        $result = Tokens\TokenFactory::buildFromHtml($html);
        $this->assertInstanceOf($expectedClassName, $result);
    }

    /**
     * @dataProvider buildFromHtmlPagstractDataProvider
     */
    public function testBuildFromHtmlPagstract($html, $expectedClassName)
    {
        new PagstractTokenizer();
        $result = Tokens\TokenFactory::buildFromHtml($html);
        $this->assertInstanceOf($expectedClassName, $result);
    }

    /**
     * @dataProvider buildFromHtmlDataProvider
     */
    public function testBuildFromHtmlThrowsException($html, $expectedClassName)
    {
        TokenFactory::$matchings = array("UnknownToken" => array(
                "start" => TokenFactory::$matchings["Text"]["start"],
                "end" => TokenFactory::$matchings["Text"]["end"]
        ));
        try {
            $result = Tokens\TokenFactory::buildFromHtml($html);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token class found', $e->getMessage());
        }
        
        // clean-up: re-init default tokenizer setup
        new MarkupTokenizer();
    }

    public function testRegisterMatchingExceptionRegisteredAlready() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
        
        try {
            Tokens\TokenFactory::registerMatching("Text", "/.*/", PHP_EOL);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('Token has been registered already', $e->getMessage());
        }
         
    }

    public function testRegisterMatchingExceptionInvalidTokenName() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
        
        try {
            Tokens\TokenFactory::registerMatching("", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('Invalid token classname given', $e->getMessage());
        }

    }
        
    public function testRegisterMatchingExceptionNoTokenClass() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
             
        try {
            Tokens\TokenFactory::registerMatching("UnknownToken", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token class found', $e->getMessage());
        }
        
    }
        
    public function testRegisterMatchingExceptionNoMatchingFound() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
             
        try {
            Tokens\TokenFactory::registerMatching("\StdClass", null, null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No matching found', $e->getMessage());
        }
        
    }

    public function testRegisterMatchingExceptionNoTokenEndSequence() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
             
        try {
            Tokens\TokenFactory::registerMatching("\StdClass", "/.*/i", "");
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token-end sequence given', $e->getMessage());
        }
        
    }

    public function testRegisterMatching() {
        TokenFactory::clearMatchings();
        TokenFactory::registerMatching("Text", "/.*/i", PHP_EOL);
        $matchings = TokenFactory::getMatchings();

        $this->assertArrayHasKey("Text", $matchings);
        $this->assertArrayHasKey("start", $matchings["Text"]);
        $this->assertArrayHasKey("end", $matchings["Text"]);
        
        // clean-up: re-init default tokenizer setup
        new MarkupTokenizer();
    }

    public function testGetMatchingFromTokenClassExceptionNoTokenClass() {
        // re-init default tokenizer setup
        new MarkupTokenizer();
             
        try {
            $matching = Tokens\TokenFactory::getTokenMatchingFromClass("UnknownToken");
        } catch (\Exception $e) {
            $this->assertInstanceOf('PHPagstract\Token\Exception\TokenFactoryException', $e);
            $this->assertContains('No token class found', $e->getMessage());
        }
        
    }
        
    public function buildFromHtmlDataProvider()
    {
        return array(
            array(
                'asdf',
                'PHPagstract\Token\Tokens\Text'
            ),
            array(
                '<asdf />',
                'PHPagstract\Token\Tokens\Element'
            ),
            array(
                '<!-- asdf -->',
                'PHPagstract\Token\Tokens\Comment'
            ),
            array(
                '<![CDATA[asdf]]>',
                'PHPagstract\Token\Tokens\CData'
            ),
            array(
                '<!DOCTYPE asdf >',
                'PHPagstract\Token\Tokens\DocType'
            ),
            array(
                '<?php asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            array(
                '<? asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            )
        );
        
        // re-init default tokenizer setup
        new MarkupTokenizer();
    }

    public function buildFromHtmlPagstractDataProvider()
    {
        return array(
            array(
                'asdf',
                'PHPagstract\Token\Tokens\Text'
            ),
            array(
                '<!-- asdf -->',
                'PHPagstract\Token\Tokens\Comment'
            ),
            array(
                '<![CDATA[asdf]]>',
                'PHPagstract\Token\Tokens\CData'
            ),
            array(
                '<!DOCTYPE asdf >',
                'PHPagstract\Token\Tokens\DocType'
            ),
            array(
                '<?php asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            array(
                '<? asdf; ?>',
                'PHPagstract\Token\Tokens\Php'
            ),
            array(
                '<asdf />',
                'PHPagstract\Token\Tokens\PagstractMarkup'
            ),
            array(
                '<asdf></asdf>',
                'PHPagstract\Token\Tokens\PagstractMarkup'
            ),
        		
        		
        	array(
        		'<pma:value pma:name=".property"></pma:value>',
        		'PHPagstract\Token\Tokens\PagstractSimpleValue'
        	),
            array(
                '<pma:value pma:name=".property" />',
                'PHPagstract\Token\Tokens\PagstractSimpleValue'
            ),

        	
            array(
                '<!--- pagstract comment -->',
                'PHPagstract\Token\Tokens\PagstractComment'
            ),
            array(
                '<pma:debug pma:name />',
                'PHPagstract\Token\Tokens\PagstractDebug'
            ),	
            array(
                '<pma:bean pma:name></pma:bean>',
                'PHPagstract\Token\Tokens\PagstractBean'
            ),
            array(
                '<pma:if-visible pma:name=".property"></pma:if-visible>',
                'PHPagstract\Token\Tokens\PagstractIfVisible'
            ),
        		
        		
            array(
                '<pma:list pma:name=".listproperty">Link</pma:list>',
                'PHPagstract\Token\Tokens\PagstractList'
            ),	
            array(
                '<pma:header>some content</pma:header>',
                'PHPagstract\Token\Tokens\PagstractListHeader'
            ),	
            array(
                '<pma:content>some content</pma:content>',
                'PHPagstract\Token\Tokens\PagstractListContent'
            ),	
            array(
                '<pma:footer>some content</pma:footer>',
                'PHPagstract\Token\Tokens\PagstractListFooter'
            ),	
            array(
                '<pma:seperator>some content</pma:seperator>',
                'PHPagstract\Token\Tokens\PagstractListSeperator'
            ),	
            array(
                '<pma:first>some content</pma:first>',
                'PHPagstract\Token\Tokens\PagstractListFirst'
            ),	
            array(
                '<pma:last>some content</pma:last>',
                'PHPagstract\Token\Tokens\PagstractListLast'
            ),	
            array(
                '<pma:even>some content</pma:even>',
                'PHPagstract\Token\Tokens\PagstractListEven'
            ),	
            array(
                '<pma:odd>some content</pma:odd>',
                'PHPagstract\Token\Tokens\PagstractListOdd'
            ),	
            array(
                '<pma:no-content>some content</pma:no-content>',
                'PHPagstract\Token\Tokens\PagstractListNoContent'
            ),	
            array(
                '<pma:nocontent>some content</pma:nocontent>',
                'PHPagstract\Token\Tokens\PagstractListNoContent'
            ),	
        		
        		
            array(
                '<pma:modList pma:name>some content</pma:modList>',
                'PHPagstract\Token\Tokens\PagstractModList'
            ),	
            array(
                '<pma:modContent>some content</pma:modContent>',
                'PHPagstract\Token\Tokens\PagstractModContent'
            ),	
            array(
                '<pma:modSeperator>some content</pma:modSeperator>',
                'PHPagstract\Token\Tokens\PagstractModSeperator'
            ),
        		
        		
            array(
                '<pma:form pma:name=".formproperty"></pma:form>',
                'PHPagstract\Token\Tokens\PagstractForm'
            ),	
        		
        		
            array(
                '<pma:switch pma:name>some content</pma:switch>',
                'PHPagstract\Token\Tokens\PagstractSwitch'
            ),	
            array(
                '<object pma:case>some content</object>',
                'PHPagstract\Token\Tokens\PagstractObject'
            ),	

        		
            array(
                '<pma:tile filename="./partial.html"></pma:tile>',
                'PHPagstract\Token\Tokens\PagstractTile'
            ),
            array(
                '<pma:tileVariable>{"some" : "json"}</pma:tileVariable>',
                'PHPagstract\Token\Tokens\PagstractTileVariable'
            ),
        		
        		
            array(
                '<a pma:name=".actionproperty">Link</a>',
                'PHPagstract\Token\Tokens\PagstractLink'
            ),
            array(
                '<area pma:name />',
                'PHPagstract\Token\Tokens\PagstractArea'
            ),
            array(
                '<input pma:name=".inputproperty" />',
                'PHPagstract\Token\Tokens\PagstractInput'
            ),
            array(
                '<select pma:name=".selectproperty" />',
                'PHPagstract\Token\Tokens\PagstractSelect'
            ),
            array(
                '<select pma:name=".selectproperty" multiple></select>',
                'PHPagstract\Token\Tokens\PagstractSelect'
            ),
        		
        	/*	
            array(
                'resource://images/dummy.png',
                'PHPagstract\Token\Tokens\PagstractResource'
            ),	
            array(
                'resource_ext://images/dummy.png',
                'PHPagstract\Token\Tokens\PagstractResource'
            ),
            array(
                'msg://messages.properties.text',
                'PHPagstract\Token\Tokens\PagstractMessage'
            ),
            */
        );
        
        // re-init default tokenizer setup
        new MarkupTokenizer();
    }

    public function testNoTypeFound()
    {
        $this->assertFalse(TokenFactory::buildFromHtml('< asdfasdf'));
    }

    /**
     * @expectedException PHPagstract\Token\Exception\TokenFactoryException
     */
    public function testExceptionInBuildFromHtml()
    {
        TokenFactory::buildFromHtml('< asdfasdf', null, true);
    }
}
