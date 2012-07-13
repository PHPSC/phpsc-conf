<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

interface ColumnFormatter
{
	/**
	 * @param string $content
	 * @param object $item
	 * @return string
	 */
	public function format($content, $item);
}