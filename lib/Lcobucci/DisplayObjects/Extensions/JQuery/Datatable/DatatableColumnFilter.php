<?php
namespace Lcobucci\DisplayObjects\Extensions\JQuery\Datatable;

class DatatableColumnFilter
{
	/**
	 * @var string
	 */
	public $type;
	
	/**
	 * @var array
	 */
	public $values;
	
	/**
	 * @param string $type
	 * @param array $values
	 */
	public function __construct($type, array $values = null)
	{
		$this->type = $type;
		$this->values = $values;
	}
}