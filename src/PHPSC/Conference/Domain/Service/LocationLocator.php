<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Repository\LocationRepository;
use PHPSC\Conference\Domain\Entity\Location;
use RuntimeException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <luis@phpsc.com.br>
 */
class LocationLocator
{
    /**
     * @var LocationRepository
     */
    private $repository;

    /**
     * @param LocationRepository
     */
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return Location
     * @throws RuntimeException
     */
    public function findById($id)
    {
        if ($event = $this->repository->findOneById($id)) {
            return $event;
        }

        throw new RuntimeException('Localidade não encontrada');
    }
}
