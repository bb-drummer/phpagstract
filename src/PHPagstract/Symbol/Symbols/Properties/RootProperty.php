<?php

namespace PHPagstract\Symbol\Symbols\Properties;

use PHPagstract\Symbol\Symbols\AbstractPropertySymbol;

/**
 *
 * @author bba
 *        
 */
class RootProperty extends AbstractPropertySymbol {
    
    /**
     */
    public function __construct() {
        parent::__construct ('root', 'root', $this);
    }
}

