<?php
/**
 * Pagstract token abstract class
 */
namespace PHPagstract\Token\Tokens;

use PHPagstract\Token\Exception\TokenizerException;
use PHPagstract\Token\PagstractTokenizer;

/**
 * Pagstract token abstract class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractAbstractToken extends AbstractToken
{
    /**
     * @var array the $matching
     */
    public static $matching = array(
        "start" => "/^\s*<pma|^\s*<object |^\s*<a |^\s*<area |^\s*<input |^\s*<select /i", 
        "end" => ">"
    );

    /**
     * nesting tags allowed?
     * 
     * @var boolean 
     */
    public $nested = true;
    
    /**
     * tag attributes
     * 
     * @var null|array 
     */
    protected $attributes;
    
    /**
     * list of mandatory attributes
     * 
     * @var array
     */
    protected $mandatoryAttributes = [];

    /**
     * list of child tokens if nesting tags is allowed
     * 
     * @var null|array[Token] 
     */
    protected $children;

    /**
     * tag name
     * 
     * @var string 
     */
    protected $name;
    
    /**
     * tag value/content
     *
     * @var mixed 
     */
    protected $value;
    
    /**
     * is this a closing tag?
     * 
     * @var boolean 
     */
    protected $isClosing;
    
    /**
     * list of valid token types
     * 
     * @var array 
     */
    protected $validTypes = array(
        Token::CDATA,
        Token::COMMENT,
        Token::DOCTYPE,
        Token::ELEMENT,
        Token::PHP,
        Token::TEXT,

        Token::CONTENIDO,

        Token::PAGSTRACT,
        Token::PAGSTRACTMARKUP, // any other markup than pagstract markup
            
        Token::PAGSTRACTCOMMENT, // special '<!--- ... -->' handling
        Token::PAGSTRACTRESOURCE, // special 'resource(_ext)://...' handling
        Token::PAGSTRACTMESSAGE, // special 'msg://...' handling

        Token::PAGSTRACTSIMPLEVALUE,
        Token::PAGSTRACTRENDERED,
            
        Token::PAGSTRACTTILE,
        Token::PAGSTRACTTILEVARIABLE,
            
        Token::PAGSTRACTBEAN,
        Token::PAGSTRACTIFVISIBLE,
            
        Token::PAGSTRACTLIST,
        Token::PAGSTRACTLISTHEADER,
        Token::PAGSTRACTLISTFOOTER,
        Token::PAGSTRACTLISTCONTENT,
        Token::PAGSTRACTLISTNOCONTENT,
        Token::PAGSTRACTLISTSEPARATOR,
        Token::PAGSTRACTLISTEVEN,
        Token::PAGSTRACTLISTODD,
        Token::PAGSTRACTLISTFIRST,
        Token::PAGSTRACTLISTLAST,
            
        Token::PAGSTRACTMODLIST,
        Token::PAGSTRACTMODSEPARATOR,
        Token::PAGSTRACTMODCONTENT,
            
        Token::PAGSTRACTSWITCH,
        Token::PAGSTRACTOBJECT,
            
        Token::PAGSTRACTFORM,
            
        Token::PAGSTRACTTEXTIMG,
            
        Token::PAGSTRACTLINK,
        Token::PAGSTRACTAREA,
        Token::PAGSTRACTINPUT,
        Token::PAGSTRACTSELECT,
            
        Token::PAGSTRACTDEBUG,

        Token::PAGSTRACTPROPERTYREFERENCE, // special '${...}' handling
        Token::PAGSTRACTPROPERTYREFERENCETEXT, // every other text around a '${...}'
    );

    /**
     * Constructor
     *
     * @param string     $type
     * @param null|Token $parent
     * @param boolean    $throwOnError
     */
    public function __construct($type, Token $parent = null, $throwOnError = false)
    {
        parent::__construct($type, $parent, $throwOnError);
        $this->throwOnError = (boolean) $throwOnError;

        $this->name = null;
        $this->value = null;
        
        $this->attributes = null;
        $this->children = null;
    }
    
    /**
     * Does the parent have an implied closing tag?
     *
     * @param string $html
     *
     * @return boolean
     */
    public function isClosingElementImplied($html)
    {
        return false;
    }

    /**
     * Will parse this element.
     *
     * @param string $html
     *
     * @return string Remaining HTML.
     */
    public function parse($html)
    {
        $html = ltrim($html);

        // Get token position.
        $positionArray = PagstractTokenizer::getPosition($html);
        $this->setLine($positionArray['line']);
        $this->setPosition($positionArray['position']);

        // Parse name.
        $this->name = $this->parseElementName($html);
      
        // Parse attributes.
        $remainingHtml = mb_substr($html, mb_strlen($this->name) + ($this->isClosing ? 2 : 1));
        
        while ( (mb_strpos($remainingHtml, '>') !== false) && 
                (preg_match("/^\s*[\/]?>/", $remainingHtml) === 0) ) {
            $remainingHtml = $this->parseAttribute($remainingHtml);
        }

        // Find position of end of tag.
        $posOfClosingBracket = mb_strpos($remainingHtml, '>');
        if ($posOfClosingBracket === false) {
            if ($this->getThrowOnError()) {
                throw new TokenizerException(
                    'Invalid element: missing closing bracket '.
                        'in line: '.$this->getLine().', position: '.$this->getPosition().''
                );
            }

            return '';
        }

        // Is self-closing?
        $posOfSelfClosingBracket = mb_strpos($remainingHtml, '/>');
        
        $remainingHtml = mb_substr($remainingHtml, $posOfClosingBracket + 1);
        
        if (($posOfSelfClosingBracket !== false)  
            && ($posOfSelfClosingBracket == $posOfClosingBracket - 1) 
        ) {
            // Self-closing element.
            return $remainingHtml;
        }

        
        // Lets close those closed-only elements that are left open.
        $closedOnlyElements = array(
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr'
        );
        if (array_search($this->name, $closedOnlyElements) !== false) {
            return $remainingHtml;
        }
        $nested = $this->nested();
        if (!$nested) { 
            return $remainingHtml;
        }
        
        // Open element.
        return $this->parseContents($remainingHtml);
    }

    /**
     * Will parse attributes.
     *
     * @param string $html
     *
     * @return string Remaining HTML.
     */
    private function parseAttribute($html)
    {
        $remainingHtml = ltrim($html);

        // Will match the first entire name/value attribute pair.
        preg_match(
            "/((([a-z0-9\-_]+:)?[a-z0-9\-_]+)(\s*=\s*)?)/i",
            $remainingHtml,
            $attributeMatches
        );

        $name = $attributeMatches[2];
        $remainingHtml = mb_substr(mb_strstr($remainingHtml, $name), mb_strlen($name));
        if (preg_match("/^\s*=\s*/", $remainingHtml) === 0) {
            // Valueless attribute.
            $this->attributes[trim($name)] = true;
        } else {
            $remainingHtml = ltrim($remainingHtml, ' =');
            if ($remainingHtml[0] === "'" || $remainingHtml[0] === '"') {
                // Quote enclosed attribute value.
                $valueMatchSuccessful = preg_match(
                    "/".$remainingHtml[0]."(.*?(?<!\\\))".$remainingHtml[0]."/s",
                    $remainingHtml,
                    $valueMatches
                );
                if ($valueMatchSuccessful !== 1) {
                    if ($this->getThrowOnError()) {
                        throw new TokenizerException(
                            'Invalid value encapsulation '.
                                'in line: '.$this->getLine().', position: '.$this->getPosition().'.'
                        );
                    }

                    return '';
                }

                $value = $valueMatches[1];
            } else {
                // No quotes enclosing the attribute value.
                preg_match("/(\s*([^>\s]*(?<!\/)))/", $remainingHtml, $valueMatches);
                $value = $valueMatches[2];
            }

            $this->attributes[trim($name)] = $value;

            // Determine remaining html.
            if ($value == '') {
                $remainingHtml = ltrim(mb_substr(ltrim($html), mb_strlen($name) + 3));
            } else {
                $remainingHtml = ltrim($html);

                // Remove attribute name.
                $remainingHtml = mb_substr($remainingHtml, mb_strlen($name));
                $posOfAttributeValue = mb_strpos($remainingHtml, $value);
                $remainingHtml = ltrim(
                    mb_substr(
                        $remainingHtml,
                        $posOfAttributeValue + mb_strlen($value)
                    )
                );
            }

            $remainingHtml = ltrim($remainingHtml, '\'" ');
        }

        return $remainingHtml;
    }

    /**
     * Will parse the contents of this element.
     *
     * @param string $html
     *
     * @return string Remaining HTML.
     */
    private function parseContents($html)
    {
        if (ltrim($html) == '') {
            return '';
        }
        
        /* do we really have tags to omit parsing for?!?
        // Don't parse contents of "iframe" element.
        if ($this->name == 'iframe') {
            return $this->parseNoContents('iframe', $html);
        }

        // Only TEXT inside a "script" element.
        if ($this->name == 'script') {
            return $this->parseForeignContents('script', $html);
        }

        // Only TEXT inside a "style" element.
        if ($this->name == 'style') {
            return $this->parseForeignContents('style', $html);
        }
        */
        
        // Parse contents one token at a time.
        $remainingHtml = $html;
        while (preg_match("/^\s*<\/\s*".$this->name."\s*>/is", $remainingHtml) === 0) {
            $token = TokenFactory::buildFromHtml(
                $remainingHtml,
                $this,
                //false 
                $this->getThrowOnError()
            );

            if ($token === false || $token->isClosingElementImplied($remainingHtml)) {
                return $remainingHtml;
            }
            
            if (!is_array($this->children)) {
                $this->children = array();
            }
            $remainingHtml = $token->parse($remainingHtml);
            $this->children[] = $token;
        }

        // Remove last token if contains only whitespace.
        if (!empty($this->children)) {
            $lastChildArray = array_slice($this->children, -1);
            $lastChild = array_pop($lastChildArray);
            if ($lastChild->isText() && trim($lastChild->getValue()) == '') {
                array_pop($this->children);
            }
        }

        // Remove remaining closing tag.
        $posOfClosingBracket = mb_strpos($remainingHtml, '>');

        return mb_substr($remainingHtml, $posOfClosingBracket + 1);
    }

    /**
     * Will get the element name from the html string.
     *
     * @param string $html
     *
     * @return null|string The element name.
     */
    private function parseElementName($html)
    {
        $elementMatchSuccessful = preg_match(
            "/(<([\/]?)(([a-z0-9\-]+:)?[a-z0-9\-]+))/i",
            //"/(<(([a-z0-9\-]+:)?[a-z0-9\-]+))/i",
            $html,
            $elementMatches
        );
        if ($elementMatchSuccessful !== 1) {
            if ($this->getThrowOnError()) {
                throw new TokenizerException('Invalid element name.');
            }

            return null;
        }
        if (!empty($elementMatches[2])) {
            $this->isClosing = true;
            return ''; 
        }
        return mb_strtolower($elementMatches[3]);
    }

    /**
     * Getter for 'attributes'.
     *
     * @return null|array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return boolean
     */
    public function hasAttributes()
    {
        return !empty($this->attributes);
    }

    /**
     * Getter for 'children'.
     *
     * @return null|array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * Getter for 'name'.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for 'value'.
     *
     * @return null|mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Getter/Setter for 'nested'.
     *
     * @return boolean
     * @return boolean
     */
    public function nested($nested = null)
    {
        if ($nested !== null) {
            $this->nested = !!$nested;
            
        }
        return $this->nested;
    }

    public function toArray()
    {
        $result = array(
            'type' => $this->getType(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'line' => $this->getLine(),
            'position' => $this->getPosition()
        );

        if (!empty($this->attributes)) {
            $result['attributes'] = array();
            foreach ($this->attributes as $name => $value) {
                $result['attributes'][$name] = $value;
            }
        }

        if (($this->children !== null)) {
            $result['children'] = array();
            if (!empty($this->children)) {
                foreach ($this->children as $child) {
                    $result['children'][] = $child->toArray();
                }
            }
        }
        
        return $result;
    }

    /**
     * check for valid type
     * {@inheritDoc}
     *
     * @see \PHPagstract\Token\Tokens\AbstractToken::isValidType()
     */
    protected function isValidType($type)
    {
        return (in_array($type, $this->validTypes));
    }
}
