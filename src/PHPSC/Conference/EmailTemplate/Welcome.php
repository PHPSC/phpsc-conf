<?php

namespace PHPSC\Conference\EmailTemplate;

class Welcome extends \Swift_Message
{
	public function __construct($placeholders = array())
	{
		$html = '<p>Olá {{name}},</p><p>Bem-vindo ao PHPSC Conference 2013.</p><p>Atenciosamente,<br />Coordenação PHPSC.</p>';
		
		foreach ($placeholders as $key => $value) {
			$html = str_replace("{{{$key}}}", $value, $html);
		}

		parent::__construct('Bem-vindo ao PHPSC', $html, 'text/html', 'UTF-8');
	}
}