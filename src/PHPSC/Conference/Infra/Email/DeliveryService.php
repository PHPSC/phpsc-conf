<?php
namespace PHPSC\Conference\Infra\Email;

use Swift_Mailer;
use Swift_Message;

/**
 * @author Rafael Kassner <kassner@gmail.com>
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class DeliveryService
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @param Swift_Mailer $mailer
     * @param string $sender
     * @param string $senderName
     */
    public function __construct(Swift_Mailer $mailer, $sender, $senderName)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->senderName = $senderName;
    }

    /**
     * @param Swift_Message $message
     * @throws \Exception
     */
    public function send(Swift_Message $message)
    {
        $message->setFrom($this->sender, $this->senderName);

        if (!(boolean) $this->mailer->send($message)) {
            throw new DeliveryException('Não foi possível enviar o e-mail');
        }
    }

    /**
     * @param string $template
     * @param array $placeholders
     * @throws EmailDeliveryException
     * @return Swift_Message
     */
    public function getMessageFromTemplate(
        $template,
        array $placeholders = array()
    ) {
        $className = __NAMESPACE__ . '\\Templates\\' . $template;

        if (!class_exists($className)) {
            throw new DeliveryException('Template não encontrado');
        }

        return new $className($placeholders);
    }
}
