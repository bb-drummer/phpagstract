<?php

namespace PHPagstract\Symbol\Symbols;

use PHPagstract\AbstractCollection;

/**
 * A SymbolCollection is a group of symbols designed to act similiar to
 * an array.
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class SymbolCollection extends AbstractCollection
{
	/**
	 * flag if item to add/set must be an instance of $type
	 *
	 * @var boolean
	 */
	protected $onlyValidType = true;
    
    
	/**
	 * valid type/classname items to add/set must ba an instance of
	 *
	 * @var string
	 */
	protected $type = 'PHPagstract\\Symbol\\Symbols\\Symbol';
    
}
