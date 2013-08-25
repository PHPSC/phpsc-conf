<?php
namespace PHPSC\Conference\Application\View\Pages\Registration;

use PHPSC\Conference\Application\View\Main;
use PHPSC\Conference\Application\View\ShareButton;
use \PHPSC\Conference\Domain\Entity\Event;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Confirmation extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    protected function getShareButton()
    {
        return new ShareButton(
            'Acabo de me inscrever no ' . $this->event->getName(),
            'Acabo de me inscrever no #phpscConf. Participe você também!',
            'http://conf.phpsc.com.br',
            'PHP_SC',
            'btn-info btn-lg',
            ''
        );
    }
}
