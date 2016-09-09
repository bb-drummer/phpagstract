<?php

namespace PHPagstract\Symbol;

/**
 *
 * @author bba
 *        
 */
class PropertyReferenceResolver {
    
    /**
     * regular expression to parse refrence string representation
     * ex: ".artikel.verknuepfungen.kacheln[0].liste[1].label"
     * 
     * @var string
     */
    //private $regex = "/([\.]?(([a-zA-Z0-9\-\_]*))(\[([0-9]*)])?)/Aig";
    private $regex = "/[\.]?([a-zA-Z0-9\-\_]*)\[?([0-9]*)]?/Aig";
    
    /**
     * context, a.k.a. data container to map the reference to
     * 
     * @var array|object
     */
    private $context = [];
    
    /**
     */
    public function __construct() {
    }
    
    /**
     * resolve a reference string to data object/array
     * 
     * @param string $reference
     * @return mixed
     */
    public function getPropertyByReference ( $reference ) {
        
        $tokens = preg_match_all($this->getRegex(), $reference);
        
        $data = $this->getContext();
        if ( strpos($reference, "../") === 0) {
            $data = $data->getParent();
        }
        
        foreach ($tokens as $key => $value) {
            $propertyName = $value[1];
            $index = $value[2];
            if ( ($index == '' ) && ($propertyName != '' ) && isset($data[$propertyName]) ) {
                // resolve property name: ".propertyName"
                $data = $data[$propertyName];
                continue;
            } elseif ( ($index != '' ) && ($index >= 0 ) && ($propertyName != '' ) && isset($data[$propertyName]) && isset($data[$propertyName][$index]) ) {
                // resolve property name and list index: ".propertyName[n]"
                $data = $data[$propertyName][$index];
                continue;
            } elseif ( ($index != '' ) && ($index >= 0 ) && ($propertyName == '' ) && isset($data[$propertyName][$index]) ) {
                // resolve a list's (deeper) index: ".propertyName[n][m]"
                $data = $data[$index];
                continue;
            } else {
                // whitespace etc found, stop searching context
                break;
            }
        }
        
        return $data;
    }
    
    /**
     * resolve a reference string to data object/array
     *
     * @param string $reference
     * @return mixed
     */
    public function getValueByReference ( $reference ) {
        return $this->getPropertyByReference($reference);
    }
    
    /**
     * get the reference parser $regex
     * 
     * @return string the $regex
     */
    public function getRegex() {
        return $this->regex;
    }

    /**
     * set the reference parser $regex
     * 
     * @param string $regex
     */
    public function setRegex($regex) {
        $this->regex = $regex;
    }

    /**
     * get the $context data container
     * 
     * @return array|object
     */
    public function getContext() {
        return $this->context;
    }

    /**
     * set the $context data container
     * 
     * @param array|object $context
     */
    public function setContext($context) {
        $this->context = $context;
    }

    
    
}
