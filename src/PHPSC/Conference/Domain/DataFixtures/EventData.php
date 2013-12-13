<?php

namespace PHPSC\Conference\Domain\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PHPSC\Conference\Domain\Entity\Event as EventEntity;
use DateTime;

class EventData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $location = $this->getReference('location');

        $event = new EventEntity();
        $event->setLocation($location);
        $event->setName('PHPSC Conference 2012');
        $event->setStartDate(DateTime::createFromFormat('Y-m-d', '2012-10-27'));
        $event->setEndDate(DateTime::createFromFormat('Y-m-d', '2012-10-27'));
        $event->setSubmissionStart(DateTime::createFromFormat('Y-m-d H:i:s', '2012-08-06 00:00:00'));
        $event->setSubmissionEnd(DateTime::createFromFormat('Y-m-d H:i:s', '2012-09-16 23:59:59'));

        $manager->persist($event);
        $manager->flush();

        $this->addReference('event', $event);
    }

    public function getOrder()
    {
        return 2;
    }

}
