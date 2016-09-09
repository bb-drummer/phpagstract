<?php

namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;

/**
 *
 * @author bba
 *        
 */
class Text extends AbstractTokenSymbol {
    
    /**
     */
    public function __construct($parent = null, $throwOnError = false) {
        parent::__construct ($parent, $throwOnError);
    }
}

