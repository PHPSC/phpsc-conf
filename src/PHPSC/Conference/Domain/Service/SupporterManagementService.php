<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Domain\Factory\SupporterFactory;
use PHPSC\Conference\Domain\Repository\SupporterRepository;

class SupporterManagementService
{
    /**
     * @var SupporterRepository
     */
    private $repository;

    /**
     * @var SupporterFactory
     */
    private $factory;

    /**
     * @param SupporterRepository $repository
     * @param SupporterFactory $factory
     */
    public function __construct(
        SupporterRepository $repository,
        SupporterFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        return $this->repository->findByEvent($event);
    }

    /**
     * @param Event $event
     * @param Company $company
     * @param string $details
     * @return Supporter
     */
    public function create(Event $event, Company $company, $details)
    {
        $supporter = $this->factory->create($event, $company, $details);

        $this->repository->append($supporter);

        return $supporter;
    }
}
