<?php

namespace PHPagstractTest\Token\Tokens;

use PHPagstract\Token\Tokens\Php;

class PhpTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        // clean-up: re-init default tokenizer setup
        new \PHPagstract\Token\MarkupTokenizer();;
    }
    
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse($html, $expectedValue, $expectedRemainingHtml)
    {
        $php = new Php();
        $remainingHtml = $php->parse($html);
        $this->assertEquals($expectedValue, $php->getValue());
        $this->assertEquals($expectedRemainingHtml, $remainingHtml);
    }

    public function parseDataProvider()
    {
        return array(
            'simple' => array(
                '<?php echo "asdf"; ?>',
                'echo "asdf";',
                ''
            ),
            'simple with whitespace' => array(
                '    <?php        echo "asdf";                ?>    g',
                'echo "asdf";',
                '    g'
            ),
            'containing element' => array(
                '<?php echo "<div>asdf</div>"; ?>',
                'echo "<div>asdf</div>";',
                ''
            ),
            'no closing tag' => array(
                '<?php echo "asdf";    ',
                'echo "asdf";',
                ''
            ),
        );
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray($html, $expectedArray)
    {
        $php = new Php();
        $php->parse($html);
        $this->assertEquals($expectedArray, $php->toArray());
    }

    public function toArrayDataProvider()
    {
        return array(
            'simple' => array(
                '<?php echo "asdf"; ?>',
                array(
                    'type' => 'php',
                    'value' => 'echo "asdf";',
                    'line' => 0,
                    'position' => 0
                )
            )
        );
    }
}
