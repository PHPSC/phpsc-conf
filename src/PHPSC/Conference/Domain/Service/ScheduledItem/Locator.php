<?php
namespace PHPSC\Conference\Domain\Service\ScheduledItem;

use DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Repository\ScheduledItemRepository;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Locator
{
    /**
     * @var ScheduledItemRepository
     */
    protected $repository;

    /**
     * @param ScheduledItemRepository $repository
     */
    public function __construct(ScheduledItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Event $event
     * @param DateTime $date
     * @return array
     */
    public function getByDate(Event $event, DateTime $date)
    {
        return $this->repository->findByDate($event, $date);
    }
}
