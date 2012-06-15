<?php
namespace Lcobucci\DisplayObjects\Extensions\JQuery\Datatable;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid;

class Datatable extends UIComponent
{
	/**
	 * @var \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
	 */
	private $table;

	/**
	 * @var array
	 */
	private $options;
	
	/**
	 * @var array
	 */
	private $filters;

	/**
	 * @var string
	 */
	private $jsCommands;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param array $columns
	 * @param array $options
	 */
	public function __construct($id, array $dataProvider, array $columns, array $options = null)
	{
		$this->table = new Datagrid($id, $dataProvider, $columns);
		$this->options = $options;
		$this->filters = array();
		$this->jsCommands = '';
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->table->getId();
	}

	/**
	 * @return \Lcobucci\DisplayObjects\Components\Datagrid\Datagrid
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getOptions()
	{
		return $this->options ? json_encode($this->options) : '';
	}

	/**
	 * Configures the $styleClass
	 *
	 * @param string $styleClass
	 */
	public function setStyleClass($styleClass)
	{
		$this->table->setStyleClass($styleClass);
	}

	/**
	 * Configures the $inlineStyle
	 *
	 * @param string $inlineStyle
	 */
	public function setInlineStyle($inlineStyle)
	{
		$this->table->setInlineStyle($inlineStyle);
	}

	/**
	 * @param string $command
	 */
	public function appendJsCommand($command)
	{
		$this->jsCommands .= $command;
	}

	/**
	 * @return string
	 */
	public function getJsCommands()
	{
		return $this->jsCommands;
	}
	
	/**
	 * @param Lcobucci\DisplayObjects\Extensions\JQuery\Datatable\DatatableColumnFilter $filter
	 */
	public function appendFilter(DatatableColumnFilter $filter)
	{
		$this->table->setDisplayFooterLabels(true);
		$this->filters[] = $filter;
	}
	
	/**
	 * @return boolean
	 */
	public function hasFilters()
	{
		return isset($this->filters[0]);
	}
	
	/**
	 * @return string
	 */
	public function getFilters()
	{
		return json_encode(array('aoColumns' => $this->filters));
	}
}