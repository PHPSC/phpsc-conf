<?php
namespace PHPSC\Conference\Domain\Service;

use \PHPSC\Conference\Domain\Repository\EventRepository;
use \RuntimeException;

class EventManagementService
{
    /**
     * @var \PHPSC\Conference\Domain\Repository\EventRepository
     */
    private $repository;

    /**
     * @param \PHPSC\Conference\Domain\Repository\EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function findCurrentEvent()
    {
        if ($event = $this->repository->findCurrentEvent()) {
            return $event;
        }

        throw new RuntimeException('Nenhum evento cadastrado');
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function findById($id)
    {
        if ($event = $this->repository->findOneById($id)) {
            return $event;
        }

        throw new RuntimeException('Nenhum evento cadastrado');
    }
}
