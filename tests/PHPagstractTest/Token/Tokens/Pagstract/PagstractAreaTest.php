<?php

namespace PHPagstractTest\Token\Tokens;

class PagstractAreaTest extends ElementTestAbstract
{
	public $elementClassname = "PagstractArea";
	
	public $elementTagname = "area";
	
	/**
	 * element test data provider
	 * {@inheritDoc}
	 * @see \PHPagstractTest\Token\Tokens\ElementTestAbstract::toArrayDataProvider()
	 */
	public function toArrayDataProvider()
	{
		$data = parent::toArrayDataProvider();
		$parsed = $data;
		foreach ($data as $key => $item) {
			if ( isset($item[1]["children"]) ) {
				unset($parsed[$key]);
			}
		}
		return $parsed;
    }
	
}
