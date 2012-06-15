<?php
namespace Lcobucci\ActionMapper\Action;

class MatchedAction
{
	/**
	 * @var Lcobucci\ActionMapper\Action\Action
	 */
	private $action;

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * Construtor da classe
	 *
	 * @param Lcobucci\ActionMapper\Action\Action $action
	 * @param string $uri
	 */
	public function __construct(Action $action, $pattern)
	{
		$this->action = $action;
		$this->pattern = $pattern;
	}

	/**
	 * Retorna ação
	 *
	 * @return Lcobucci\ActionMapper\Action\Action
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}
}