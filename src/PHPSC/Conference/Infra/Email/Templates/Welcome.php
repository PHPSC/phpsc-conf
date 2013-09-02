<?php
namespace PHPSC\Conference\Infra\Email\Templates;

use Swift_Message;

/**
 * @author Rafael Kassner <kassner@gmail.com>
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Welcome extends Swift_Message
{
    public function __construct($placeholders = array())
    {
        $html = '<p>Olá {{name}}!</p>'
                . '<p>Bem-vindo ao PHPSC Conference.</p>'
                . '<p>Atenciosamente,<br />Coordenação PHPSC.</p>';

        foreach ($placeholders as $key => $value) {
            $html = str_replace("{{{$key}}}", $value, $html);
        }

        parent::__construct(
            'Bem-vindo ao PHPSC Conference',
            $html,
            'text/html',
            'UTF-8'
        );
    }
}
