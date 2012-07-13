<?php
namespace Lcobucci\DisplayObjects\Components\Datagrid;

use \Lcobucci\DisplayObjects\Core\ItemRenderer;
use \InvalidArgumentException;

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
	 * @var callable|\Lcobucci\DisplayObjects\Components\Datagrid\ColumnFormatter
	 */
	private $formatter;

	/**
	 * @param string $label
	 * @param \Lcobucci\DisplayObjects\Core\ItemRenderer|string $content
	 * @param string $class
	 * @param callable|\Lcobucci\DisplayObjects\Components\Datagrid\ColumnFormatter $formatter
	 */
	public function __construct($label, $content, $class = '', $formatter = null)
	{
		$this->label = $label;
		$this->content = $content;
		$this->class = $class;

		$this->setFormatter($formatter);
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
			$content = $this->content->render($item);
		} else {
			$content = $this->renderProperty($item);
		}

		if ($this->formatter !== null) {
			$content = $this->formatContent($content, $item);
		}

		return $content;
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

	/**
	 * @param callable|\Lcobucci\DisplayObjects\Components\Datagrid\ColumnFormatter $formatter
	 * @throws \InvalidArgumentException
	 */
	public function setFormatter($formatter)
	{
		if ($formatter === null) {
			return;
		}

		if (!is_callable($formatter)
		&& !$formatter instanceof ColumnFormatter) {
			throw new InvalidArgumentException(
			'Formatter must be a callable or an instance of ColumnFormatter'
			);
		}

		$this->formatter = $formatter;
	}

	/**
	 * @param string $content
	 * @param object $item
	 * @return string
	 */
	protected function formatContent($content, $item)
	{
		if (is_callable($this->formatter)) {
			return call_user_func($this->formatter, $content, $item);
		}

		if ($this->formatter instanceof ColumnFormatter) {
			return $this->formatter->format($content, $item);
		}
	}
}