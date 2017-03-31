<?php
/**
 * PHPagstract list token symbol class
 */
namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;
use PHPagstract\Token\Tokens\Token;
use PHPagstract\Symbol\Symbols\Properties\ListProperty;

/**
 * PHPagstract list token symbol class
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PagstractList extends PagstractMarkup
{
    
    /**
     * class constructor
     * 
     * @param AbstractTokenSymbol $parent
     * @param string              $throwOnError
     */
    public function __construct($parent = null, $throwOnError = false) 
    {
        parent::__construct($parent, $throwOnError);
    }

    /**
     * convert symbol to string representation
     *
     * @return string
     */
    public function toString() 
    {   
        
        $attr = ($this->getAttributes());
        
        /**
 * @var AbstractPropertySymbol $property 
*/
        $property = null;
        if (isset($attr['pma:name'])) {
            $property = $this->getPropertyResolver()->getPropertyByReference($attr['pma:name']);
        }
        if ($property === null) {
            return '';
        }
        if (!($property instanceof ListProperty) ) {
            /*if ( $this->config()->throwOnError() ) {
				echo '<pre>property: '.htmlentities(print_r( $property->getType(), true )).'</pre>'; flush();
				echo '<pre>property: '.htmlentities(print_r( $property->get('items'), true )).'</pre>'; flush();
    			throw new RendererException("invalid property to use as list '".$attr['pma:name']."'");
    		}*/
            return '';
        }

        $result = '';
        
        // detect elements: header, footer, ...
        $header    = $this->getListHeader();
        $footer    = $this->getListFooter();
        $first     = $this->getListFirst();
        $last      = $this->getListLast();
        
        $even      = $this->getListEven();
        $odd       = $this->getListOdd();
        
        $content   = $this->getListContent();
        $noContent = $this->getListNoContent();
        $separator = $this->getListSeparator();

        // temporarily add list context to scope
        $this->getPropertyResolver()->addScope($property);
        
        // process header
        if ($header !== null) {
            $result .= $this->getRenderer()->render($header->getChildren());
        }
        
        // iterate the items
        $listItems = $property->get('items');
        $itemCount = count($listItems);
        if ($itemCount == 0) {
            // process no-content
            if ($noContent !== null) {
                $result .= $this->getRenderer()->render($noContent->getChildren());
            }
            
        } else {
        
            // process the item
            foreach ($listItems as $itemIndex => $listItem) {
                $itemScopeReference = $listItems[$itemIndex]; // $attr['pma:name'] . '['.$index.']';
                
                // temporarily add list item reference to scope
                $this->getPropertyResolver()->addScope($itemScopeReference);
                
                
                if (($itemIndex == 0) && ($first !== null) ) {
                    // process first item
                    $result .= $this->getRenderer()->render($first->getChildren());
                    
                } else if (($itemIndex == $itemCount-1) && ($last !== null) ) {
                    // process last item
                    $result .= $this->getRenderer()->render($last->getChildren());
                
                } else if (( ($itemIndex+1) % 2 == 0 ) && ($even !== null) ) {
                    // process even item
                    $result .= $this->getRenderer()->render($even->getChildren());
                
                } else if (( ($itemIndex+1) % 2 == 1 ) && ($odd !== null) ) {
                    // process odd item
                    $result .= $this->getRenderer()->render($odd->getChildren());
                
                } else {
                    if ($content === null) {
                        // process list symbol children if there is no content item
                        $result .= $this->getRenderer()->render($this->getChildren());
                        
                    } else {
                        // process content item
                        $result .= $this->getRenderer()->render($content->getChildren());
                        
                    }
                    
                }
                
                
                if ($itemIndex < ($itemCount-1)) {
                    // process separator
                    if ($separator !== null) {
                        $result .= $this->getRenderer()->render($separator->getChildren());
                    }
                }
                
                // remove list item reference from scope
                $this->getPropertyResolver()->unsetLastScope();
                
            }
        
        }
        
        // process footer
        if ($footer !== null) {
            $result .= $this->getRenderer()->render($footer->getChildren());
        }
        
        // remove list context from scope
        $this->getPropertyResolver()->unsetLastScope();
        
        return $result;
        
    }
    
    /**
     * detect list element symbol by name
     * 
     * @param  string $tokenName
     * @return null|mixed
     */
    private function getChildTokenSymbol( $tokenSymbolName )
    {
        if ($this->hasChildren() ) {
            $children = $this->getChildren()->getIterator();
            $children->rewind();

            /**
 * @var AbstractTokenSymbol $child 
*/
            $child = $children->current();
            while ($child !== null) {
                
                if ($child->getName() == $tokenSymbolName ) {
                    return $child;
                }
                $children->next();
                $child = $children->current();
            }
        }
        return null;
    }
    
    /**
     * detect list-header element
     *
     * @return null|PagstractListHeader
     */
    private function getListHeader()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTHEADER);
    }
    
    /**
     * detect list-footer element
     *
     * @return null|PagstractListFooter
     */
    private function getListFooter()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTFOOTER);
    }
    
    /**
     * detect list-first element
     *
     * @return null|PagstractListFrist
     */
    private function getListFirst()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTFIRST);
    }
    
    /**
     * detect list-last element
     *
     * @return null|PagstractListLast
     */
    private function getListLast()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTLAST);
    }
    
    /**
     * detect list-even element
     *
     * @return null|PagstractListEven
     */
    private function getListEven()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTEVEN);
    }
    
    /**
     * detect list-odd element
     *
     * @return null|PagstractListOdd
     */
    private function getListOdd()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTODD);
    }
    
    /**
     * detect list-content element
     *
     * @return null|PagstractListContent
     */
    private function getListContent()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTCONTENT);
    }
    
    /**
     * detect list-no-content element
     *
     * @return null|PagstractListNoContent
     */
    private function getListNoContent()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTNOCONTENT);
    }
    
    /**
     * detect list-separator element
     *
     * @return null|PagstractListSeparator
     */
    private function getListSeparator()
    {
        return $this->getChildTokenSymbol(Token::PAGSTRACTLISTSEPARATOR);
    }
    
}

