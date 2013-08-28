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
		return (boolean) $this->mailer->send($message);
	}
}