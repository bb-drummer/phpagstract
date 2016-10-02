<?php
namespace PHPagstract\Page\Resolver;

use PHPagstract\Page\ScopesTrait;
use PHPagstract\Page\StreamTrait;
use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;

/**
 * PHPagstract generic property resolver class
 *
 * generic methods to resolve property references 
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/groups/zf2
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PropertyResolver
{
    use StreamTrait;
    use ScopesTrait;
    
    /**
     * regular expression to parse reference string representation
     * ex: ".artikel.verknuepfungen.kacheln[0].liste[1].label"
     *
     * @var string|RegEx
     */
    private $parseRegex = "/(\\.\\.\\/\\.|\\.)?([a-zA-Z0-9\\-\\_]*)\\[?([0-9]*)]?/i";
    
    /**
     * regular expression to match invalid characters in a reference string representation
     * aka, every character which is not allowed in a referencestring like:
     * ".artikel().verknuepfungen,kacheln[0]/liste[1].label"
     *
     * @var string|RegEx
     */
    private $errorRegex = "/([^a-zA-Z0-9\-\_\]\[\.\/])/i";
    
    /**
     * throw exception on error?
     * 
     * @var boolean 
     */
    protected $throwOnError;
    
    /**
     * @param boolean $throwOnError throw exception on error?
     */
    public function __construct(AbstractPropertySymbol $dataStream = null, $throwOnError = false) 
    {
        if ($dataStream !== null) {
            $this->setStream($dataStream);
            $stream = $this->getStream();
            $this->addScope($stream);
            $scope = $this->getRootScope();
            $this->setContext($scope);
        }
        $this->throwOnError = $throwOnError;
    }
    
    /**
     * parse a reference string into parts
     *
     * @param  string $reference
     * @return array
     */
    public function parsePropertyReferenceString($reference)
    {
    
        $tokens = array();
        preg_match_all($this->parseRegex, $reference, $tokens);
        $errors = preg_match_all($this->errorRegex, $reference);
        if (($errors !== false) && ($errors > 0)) {
            return array();
        }
        $parts = array();
        foreach ($tokens[2] as $idx => $property) {
            if (!empty($property)) {
                $parts[] = array(
                        $tokens[1][$idx], // dot/parent
                        $tokens[2][$idx], // property-name
                        $tokens[3][$idx], // list index
                );
            }
        }
        return $parts;
    }
    
    /**
     * resolve a reference string to data object/array
     *
     * @param  string $reference
     * @return mixed
     */
    public function resolvePropertyByReference($reference)
    {
        
    
        if (strpos($reference, "../") === 0) {
            // we have a parental reference here, so get parent and recurse
            $data = $this->getContext()->getParent();
            $reference = mb_substr($reference, 3);
            $this->setContext($data);
        }
    
        $data = $this->getContext();
    
        $tokens = $this->parsePropertyReferenceString($reference);
        foreach ($tokens as $value) {
            $propertyName = $value[1];
            $index = $value[2];
    
            $type = $data->getType();
            if (in_array($type, array('root', 'object'))) {
                $properties = $data->get('properties');
                if (isset($properties->$propertyName)) {
                    $data = $properties->$propertyName;
                }
                $items = $data->get('items');
                if (($index != '') && isset($items[$index])) {
                    $data = $items[$index];
                }
            }
        }
    
        $rootContext = $this->getContext();
        if (($rootContext->getName() == $data->getName()) && ($rootContext->getParent()->getName() == $data->getParent()->getName())) {
            return null;
        }
    
        return $data;
    }
    
    /**
     * resolve a reference string to data object/array
     *
     * @param  string $reference
     * @return mixed
     */
    public function findPropertyInScopes($reference)
    {
        // process scopes in reverse order
        $scopes = $this->getScopes();
        $scopes = array_reverse($scopes);
        // remember current content
        $currentContext = $this->getContext();
        $propertyInScope = $this->getRootScope();
        foreach ($scopes as $scope) {
            // set current scope to resolver context
            $this->setContext($scope);
            $propertyInScope = $this->resolvePropertyByReference($reference);
            if ($propertyInScope !== null) {
                // could resolve property in current scope, 
                // return it and reset context
                $this->setContext($currentContext);
                return $propertyInScope;
            }
        }
        
        // no property found, reset context
        $this->setContext($currentContext);
        return $propertyInScope;
    }
    
    /**
     * resolve a reference string to data object/array
     *
     * @param  string $reference
     * @return mixed
     */
    public function getPropertyByReference($reference)
    {
        return $this->findPropertyInScopes($reference);
    }
    
    /**
     * resolve a reference string to data object/array
     *
     * @param  string $reference
     * @return mixed
     */
    public function getValueByReference($reference)
    {
        $property = $this->getPropertyByReference($reference);
        switch ($property->getType())
        {
    
        case 'object': $properties =  $property->get('properties');
            return $properties;
    
        case 'list': $list = $property->get('items');
            return $list;
    
        default: $value = $property->getProperty();
            return $value;
    
        }
    }
    
    
    
    /**
     * context, a.k.a. data container to map the reference to
     *
     * @var array|object
     */
    private $context = null;
    
    /**
     * get the $context data container
     *
     * @return array|object
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * set the $context data container
     *
     * @param array|object $context
     */
    public function setContext(&$context)
    {
        $this->context = $context;
    }    
}

