<?php

namespace PHPSC\Conference\Domain\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PHPSC\Conference\Domain\Entity\Location as LocationEntity;

class LocationData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $location = new LocationEntity();
        $location->setName('UNIVALI São José');
        $location->setDescription('Campus São José da Universidade do Vale do Itajaí');

        $manager->persist($location);
        $manager->flush();

        $this->addReference('location', $location);
    }

    public function getOrder()
    {
        return 1;
    }

}
