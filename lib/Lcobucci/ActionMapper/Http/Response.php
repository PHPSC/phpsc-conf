<?php
namespace Lcobucci\ActionMapper\Http;

use Lcobucci\ActionMapper\Core\Application;

class Response
{
	/**
	 * @var \Lcobucci\ActionMapper\Core\Application
	 */
	private $application;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @param Application $application
	 */
	public function __construct(Application $application)
	{
		$this->application = $application;
		$this->headers = array();
		$this->content = '';

		$this->contentType(ContentType::HTML, 'UTF-8');
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @param string $content
	 */
	public function appendContent($content)
	{
		$this->content .= $content;
	}

	/**
	 * @param string $path
	 */
	public function redirect($path)
	{
		$this->headers[] = 'Location: ' . $this->application->getUrl() . $path;

		$this->send();
	}

	public function contentType($type, $charset)
	{
		$this->headers[] = 'Content-Type: ' . $type . '; charset=' . $charset;
	}

	public function statusCode($status)
	{
		$this->headers[] = $_SERVER['SERVER_PROTOCOL'] . ' ' . $status;
	}

	/**
	 * Sends the response headers
	 */
	protected function sendHeaders()
	{
		if (headers_sent()) {
			return;
		}

		foreach ($this->headers as $header) {
			header($header);
		}
	}

	/**
	 * Sends the response content
	 */
	protected function sendContent()
	{
		echo $this->content;
	}

	/**
	 * Sends the application response
	 */
	public function send()
	{
		$this->sendHeaders();
		$this->sendContent();

		die();
	}
}