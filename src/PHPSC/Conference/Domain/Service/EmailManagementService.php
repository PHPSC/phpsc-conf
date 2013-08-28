<?php

namespace PHPSC\Conference\Domain\Service;

use \PHPSC\Conference\Domain\Entity\User;
use \Swift_Mailer;
use \Swift_Message;

class EmailManagementService
{
	protected $mailer;

	public function __construct(Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function send(Swift_Message $message)
	{
		$message->setFrom('contato@phpsc.com.br', 'PHPSC Conference');
		$result = (boolean) $this->mailer->send($message);

		if (!$result) {
			throw new \Exception('Não foi possível enviar o e-mail');
		}
	}

	public function getMessageFromTemplate($template, $placeholders = array())
	{
		$className = '\\PHPSC\\Conference\\EmailTemplate\\' . $template;

		if (!class_exists($className)) {
			throw new \Exception('Template não existe');
		}

		return new $className($placeholders);
	}
}