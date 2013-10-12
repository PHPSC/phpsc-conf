<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Repository\SupporterRepository;
use PHPSC\Conference\Domain\Entity\Event;

class SupporterManagementService
{
    /**
     * @var SupporterRepository
     */
    private $repository;

    /**
     * @param SupporterRepository $repository
     */
    public function __construct(SupporterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        return $this->repository->findByEvent($event);
    }
}
