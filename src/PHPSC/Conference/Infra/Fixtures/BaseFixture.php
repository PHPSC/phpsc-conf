<?php
namespace PHPSC\Conference\Infra\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\Fixture\Persistence\EntityManagerFixture;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
abstract class BaseFixture implements EntityManagerFixture
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function setEntityManager(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string|object $entity
     */
    protected function forceAssignedIds($entity)
    {
        $metadata = $this->manager->getClassMetaData(is_string($entity) ? $entity : get_class($entity));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
    }
}
