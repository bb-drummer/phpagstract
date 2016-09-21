<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\PagstractAbstractToken;

class ElementTestAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * classes namespace
     * @var string
     */
    const NS = "PHPagstract\\Token\Tokens\\";
    
    /**
     * current element classname
     * @var string
     */
    public $elementClassname = 'PagstractAbstractToken';

    /**
     * current tag-name classname
     * @var string
     */
    public $elementTagname = 'pma:test';
    
    /**
     * list of tag-names from elements with no children
     * @var array
     */
    public $noChildrenElements = array('area', 'input', 'pma:debug');

    /**
     * get (fully) qualified classname
     * @param string $classname
     * @return string
     */
    public function getElementClassname($classname)
    {
        if ( !class_exists($classname) ) {
            if ( class_exists(self::NS.$classname) ) {
                return (self::NS.$classname);
            }
        }
        return $classname;
    }
    
    /**
     * create a (empty) element helper
     * @param string $tag
     * @param boolean $selfClosing
     * @return PagstractAbstractToken
     */
    private function createElement($tag, $selfClosing = false)
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        if (!$selfClosing) {
            $element->parse('<' . $tag . '></' . $tag . '>');
        } else {
            $element->parse('<' . $tag . '/>');
        }

        return $element;
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, $expectedName, $expectedRemainingHtml, $debug = false)
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        $remainingHtml = $element->parse($html);
        if ($debug) {
            var_dump($html, $element->toArray());
        }
        $this->assertEquals($expectedName, $element->getName(), 'Testing name.');
        $this->assertEquals($expectedRemainingHtml, $remainingHtml, 'Testing remaining HTML.');
    }

    public function testAttributes()
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        $this->assertFalse($element->hasAttributes());
        $element->parse('<' . $this->elementTagname . ' class="asdf" />');
        $this->assertTrue($element->hasAttributes());
        $this->assertEquals(
            array('class' => 'asdf'),
            $element->getAttributes()
        );
    }

    public function testChildren()
    {
        if (in_array($this->elementTagname, $this->noChildrenElements)) return ;
        $classname = $this->getElementClassname($this->elementClassname);
	    $element = new $classname();
        if ( $element->nested === true ) {
	        $element = new $classname();
	        $this->assertFalse($element->hasChildren());
	        //var_dump($this->elementTagname);
	        $element->parse('<' . $this->elementTagname . '>asdf</' . $this->elementTagname . '>');
	        $this->assertTrue($element->hasChildren());
	        $this->assertEquals(1, count($element->getChildren()));
        }
    }
    
    /**
     * @expectedException PHPagstract\Token\Exception\TokenizerException
     */
    public function testExceptionInParse()
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname(null, true);
        $element->parse('<' . $this->elementTagname);
    }

    /**
     * @expectedException PHPagstract\Token\Exception\TokenizerException
     */
    public function testExceptionInParseElementName()
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        $this->assertEquals('', $element->parse('<?php'));

        $element = new $classname(null, true);
        $element->parse('<?php');
    }

    /**
     * @expectedException PHPagstract\Token\Exception\TokenizerException
     */
    public function testExceptionInParseAttribute()
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        $this->assertEquals('', $element->parse('<' . $this->elementTagname . ' foo=\'bar" />'));

        $element = new $classname(null, true);
        $element->parse('<' . $this->elementTagname . ' foo=\'bar" />');
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray, $debug = false)
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
        $element->parse($html);
        if ($debug) {
            var_dump($html, $element->toArray());
        }

        /*if ( $classname::$nested === false ) {
        	unset($expectedArray["children"]);
        }*/
        $this->assertEquals($expectedArray, $element->toArray());
    }

    public function testNested()
    {
        $classname = $this->getElementClassname($this->elementClassname);
        $element = new $classname();
    	$current = $element->nested();
    	
    	$this->assertTrue($element->nested(true));
    	$this->assertFalse($element->nested(false));
    	
    	$this->assertEquals($current, $element->nested($current));
    }
    
    //
    // data providers
    //

    public function parseDataProvider()
    {
        return array(
            'simple test' => array(
                '<' . $this->elementTagname . ' />',
                '' . strtolower($this->elementTagname) . '',
                ''
            ),
        );
    }
    
    public function toArrayDataProvider()
    {
        return array(
            'simple closed' => array(
                '<' . $this->elementTagname . '/>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'value' => null
                )
            ),
            'simple closed followed by text' => array(
                '<' . $this->elementTagname . '/>asdfasdf',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'value' => null
                )
            ),
            'closed with valueless attribute' => array(
                '<' . $this->elementTagname . ' foo1 />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo1' => true
                    ),
                    'value' => null
                )
            ),
            'closed with unquoted attribute' => array(
                '<' . $this->elementTagname . ' foo2=bar2 />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo2' => 'bar2'
                    ),
                    'value' => null
                )
            ),
            'closed with single-quoted attribute' => array(
                '<' . $this->elementTagname . ' foo3=\'bar3\' />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo3' => 'bar3'
                    ),
                    'value' => null
                )
            ),
            'closed with double-quoted attribute' => array(
                '<' . $this->elementTagname . ' foo4="bar4" />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo4' => 'bar4'
                    ),
                    'value' => null
                )
            ),
            'closed with empty double-quoted attribute' => array(
                '<' . $this->elementTagname . ' foo4="" />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo4' => ''
                    ),
                    'value' => null
                )
            ),
            'closed with 1 attribute containing equals sign' => array(
                '<' . $this->elementTagname . ' foo="bar=bar2" />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo' => 'bar=bar2'
                    ),
                    'value' => null
                )
            ),
            'closed with pseudo-empty attribute' => array(
                '<' . $this->elementTagname . ' disabled="disabled"/>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'disabled' => 'disabled'
                    ),
                    'value' => null
                )
            ),
            'closed with multiple attributes' => array(
                '<' . $this->elementTagname . '     foo1="bar1"     foo2="bar2" foo3="bar3"      />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo1' => 'bar1',
                        'foo2' => 'bar2',
                        'foo3' => 'bar3'
                    ),
                    'value' => null
                )
            ),
            'closed with all attributes' => array(
                '<' . $this->elementTagname . ' foo1 foo2=bar2 foo3=\'bar3\\\'bar3\' foo4="bar4\"bar4" />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo1' => true,
                        'foo2' => 'bar2',
                        'foo3' => 'bar3\\\'bar3',
                        'foo4' => 'bar4\\"bar4'
                    ),
                    'value' => null
                )
            ),
            'closed with all attributes - reordered' => array(
                '<' . $this->elementTagname . ' foo4="bar4\"bar4" foo2=bar2 foo1 foo3=\'bar3\\\'bar3\' />',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo4' => 'bar4\\"bar4',
                        'foo2' => 'bar2',
                        'foo1' => true,
                        'foo3' => 'bar3\\\'bar3'
                    ),
                    'value' => null
                )
            ),
            'simple open' => array(
                '<' . $this->elementTagname . '></' . $this->elementTagname . '>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'value' => null
                )
            ),
            'simple open with text content' => array(
                '<' . $this->elementTagname . '>foo</' . $this->elementTagname . '>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'children' => array(
                        array(
                            'type' => 'text',
                            'value' => 'foo',
                            'line' => 0,
                            'position' => 0
                        )
                    ),
                    'value' => null
                )
            ),
            'open with text content and whitespace' => array(
                '<' . $this->elementTagname . ' foo="bar" >   foo-bar    </' . $this->elementTagname . '>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'attributes' => array(
                        'foo' => 'bar'
                    ),
                    'children' => array(
                        array(
                            'type' => 'text',
                            'value' => ' foo-bar ',
                            'line' => 0,
                            'position' => 0
                        )
                    ),
                    'value' => null
                )
            ),
            'multibyte characters' => array(
                '<' . $this->elementTagname . '>לֶף־בֵּית</' . $this->elementTagname . '>',
                array(
                    'type' => '' . $this->elementClassname . '',
                    'name' => '' . strtolower($this->elementTagname) . '',
                    'line' => 0,
                    'position' => 0,
                    'children' => array(
                        array(
                            'type' => 'text',
                            'value' => 'לֶף־בֵּית',
                            'line' => 0,
                            'position' => 0
                        )
                    ),
                    'value' => null
                )
            ),
        );
    }


}
