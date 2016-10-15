<?php

namespace PHPagstractTest\Token\Tokens;

class PagstractLinkTest extends ElementTestAbstract
{
    public $elementClassname = "PagstractLink";
    
    public $elementTagname = "a";
    
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
    
    //
    // data providers
    //

    public function toArrayDataProvider()
    {
        $data = parent::toArrayDataProvider();
        $data['simple open with text content'] = array(
               '<' . $this->elementTagname . '>foo</' . $this->elementTagname . '>',
                array(
                   'type' => '' . $this->elementClassname . '',
                   'name' => '' . strtolower($this->elementTagname) . '',
                   'line' => 0,
                   'position' => 0,
                   'value' => null
                )
        );
        $data['open with text content and whitespace'] = array(
               '<' . $this->elementTagname . ' foo="bar" >   foo-bar    </' . $this->elementTagname . '>',
               array(
                   'type' => '' . $this->elementClassname . '',
                   'name' => '' . strtolower($this->elementTagname) . '',
                   'line' => 0,
                   'position' => 0,
                   'value' => null,
                   'attributes' => array(
                       'foo' => 'bar'
                   ),
                )
        );
        $data['multibyte characters'] = array(
               '<' . $this->elementTagname . '>לֶף־בֵּית</' . $this->elementTagname . '>',
                array(
                   'type' => '' . $this->elementClassname . '',
                   'name' => '' . strtolower($this->elementTagname) . '',
                   'line' => 0,
                   'position' => 0,
                   'value' => null
                )
        );
        
        return $data;
    }

}
