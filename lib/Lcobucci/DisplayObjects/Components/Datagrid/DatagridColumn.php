<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

use \Lcobucci\DisplayObjects\Core\ItemRenderer;

class DatagridColumn
{
	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var \Lcobucci\DisplayObjects\Core\ItemRenderer|string
	 */
	private $content;

	/**
	 * @var string
	 */
	private $class;

	/**
	 * @param string $label
	 * @param \Lcobucci\DisplayObjects\Core\ItemRenderer|string $content
	 * @param string $class
	 */
	public function __construct($label, $content, $class = '')
	{
		$this->label = $label;
		$this->content = $content;
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param object $item
	 * @return \Lcobucci\DisplayObjects\Core\ItemRenderer|string
	 */
	public function getContent($item)
	{
		if ($this->content instanceof ItemRenderer) {
			return $this->content->render($item);
		} else {
			return $this->renderProperty($item);
		}
	}

	/**
	 * @param object $item
	 * @return mixed
	 */
	protected function renderProperty($item)
	{
		$props = explode('.', $this->content);

		foreach ($props as $property) {
			if (is_null($item)) {
				return $item;
			}

			if (strpos($property, '(') !== false) {
				$item = $this->callMethod($item, $property);
			} elseif (method_exists($item, 'get' . ucfirst($property))) {
				$item = $item->{'get' . ucfirst($property)}();
			} elseif (property_exists($item, $property)) {
				$item = $item->$property;
			} else {
				return $this->content;
			}
		}

		return $item;
	}

	protected function callMethod($item, $method)
	{
		return eval('return $item->' . $method . ';');
	}
}