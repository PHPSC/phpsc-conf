<?php
namespace PHPSC\Conference\Infra\Email\Templates;

use Swift_Message;

/**
 * @author Rafael Kassner <kassner@gmail.com>
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class PaymentConfirmation extends Swift_Message
{
    /**
     * @param array $placeholders
     */
    public function __construct($placeholders = array())
    {
        $html = '<p>Olá {{user_name}}!</p>'
                . '<p>Recebemos a confirmação do pagamento para sua inscrição '
                . 'no {{event_name}}. Nos vemos por lá!.</p><p>Atenciosamente,'
                . '<br />Coordenação PHPSC.</p>';

        foreach ($placeholders as $key => $value) {
            $html = str_replace("{{{$key}}}", $value, $html);
        }

        parent::__construct(
            $placeholders['event_name'] . ': Inscrição confirmada',
            $html,
            'text/html',
            'UTF-8'
        );
    }
}
