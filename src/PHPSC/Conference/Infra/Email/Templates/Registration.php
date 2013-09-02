<?php
namespace PHPSC\Conference\Infra\Email\Templates;

use Swift_Message;

/**
 * @author Rafael Kassner <kassner@gmail.com>
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Registration extends Swift_Message
{
    public function __construct($placeholders = array())
    {
        $html = '<p>Olá {{user_name}}!</p>'
                . '<p>Recebemos sua inscrição para o {{event_name}}. A '
                . 'inscrição só é válida após a confirmação da instituição '
                . 'financeira.</p><p>Atenciosamente,<br />Coordenação PHPSC.</p>';

        foreach ($placeholders as $key => $value) {
            $html = str_replace("{{{$key}}}", $value, $html);
        }

        parent::__construct(
            $placeholders['event_name'] . ': Inscrição recebida',
            $html,
            'text/html',
            'UTF-8'
        );
    }
}
