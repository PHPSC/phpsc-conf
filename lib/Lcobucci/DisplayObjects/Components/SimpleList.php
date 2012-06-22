<?php
namespace Lcobucci\DisplayObjects\Components;

use Lcobucci\DisplayObjects\Core\ItemRenderer;
use Lcobucci\DisplayObjects\Core\UIComponent;

class SimpleList extends UIComponent
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
	 * @var Lcobucci\DisplayObjects\Core\ItemRenderer
	 */
	protected $renderer;

	/**
	 * @param string $id
	 * @param array $dataProvider
	 * @param ItemRenderer $renderer
	 */
	public function __construct($id, array $dataProvider, ItemRenderer $renderer)
	{
		$this->id = $id;
		$this->dataProvider = $dataProvider;
		$this->renderer = $renderer;
	}

	/**
	 * @return string
	 */
	protected function getId()
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	protected function getDataProvider()
	{
		return $this->dataProvider;
	}


	/**
	 * @return Lcobucci\DisplayObjects\Core\ItemRenderer
	 */
	protected function getRenderer()
	{
		return $this->renderer;
	}
}