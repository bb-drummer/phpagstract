<?php

namespace PHPagstract\Symbol\Symbols\Tokens;

use PHPagstract\Symbol\Symbols\AbstractTokenSymbol;

/**
 * PHPagstract token symbol class
 *
 * @package     PHPagstract
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class Php extends AbstractTokenSymbol {
    
	/**
	 */
	public function __construct($parent = null, $throwOnError = false) {
		parent::__construct ($parent, $throwOnError);
	}
}

