<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\UI\Component;

class Index extends Component
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
}
