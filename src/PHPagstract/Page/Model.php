<?php

namespace PHPagstract\Page;

use PHPagstract\Page\PageModelAbstract;

/**
 * page-model object class 
 *
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
class PageModel extends PageModelAbstract {
	
	/**
	 *
	 * @param string $name        	
	 *
	 * @param string $sourcespath        	
	 *
	 * @param mixed $data        	
	 *
	 * @param string $resources        	
	 *
	 * @param string $resources_ext        	
	 *
	 */
	public function __construct($name, $sourcespath, $data = array(), $resources = "./", $resources_ext = "./") {
		parent::__construct ( $name, $sourcespath, $data, $resources = "./", $resources_ext = "./" );
	}
}

