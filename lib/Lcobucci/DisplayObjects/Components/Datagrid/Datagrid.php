<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

use Lcobucci\DisplayObjects\Core\UIComponent;

class Datagrid extends UIComponent
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $dataProvider;

	/**
	 * @var array
	 */
	protected $columns;

	/**
	 * @var string
	 */
	protected $styleClass;

	/**
	 * @var string
	 */
	protected $inlineStyle;

	/**
	 * @var boolean
	 */
	protected $displayFooterLabels;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param array $columns
	 * @param boolean $displayFooterLabels
	 */
	public function __construct($id, array $dataProvider, array $columns, $displayFooterLabels = false)
	{
		$this->id = $id;
		$this->dataProvider = $dataProvider;
		$this->columns = $columns;
		$this->displayFooterLabels = $displayFooterLabels;
	}

	/**
	 * Returns the $id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the $dataProvider
	 *
	 * @return array
	 */
	protected function getDataProvider()
	{
		return $this->dataProvider;
	}

	/**
	 * Returns the $columns
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Returns the $styleClass
	 *
	 * @return string
	 */
	public function getStyleClass()
	{
		return $this->styleClass;
	}

	/**
	 * Configures the $styleClass
	 *
	 * @param string $styleClass
	 */
	public function setStyleClass($styleClass)
	{
		$this->styleClass = $styleClass;
	}

	/**
	 * Returns the $inlineStyle
	 *
	 * @return string
	 */
	public function getInlineStyle()
	{
		return $this->inlineStyle;
	}

	/**
	 * Configures the $inlineStyle
	 *
	 * @param string $inlineStyle
	 */
	public function setInlineStyle($inlineStyle)
	{
		$this->inlineStyle = $inlineStyle;
	}

	/**
	 * @param boolean $displayFooterLabels
	 */
	public function setDisplayFooterLabels($displayFooterLabels)
	{
		$this->displayFooterLabels = $displayFooterLabels;
	}

	/**
	 * @return boolean
	 */
	public function willDisplayLabelsOnFooter()
	{
		return $this->displayFooterLabels;
	}
}