<?php
namespace PHPSC\Conference\Infra\Fixtures;

use DateTime;
use Doctrine\Fixture\Sorter\DependentFixture;
use PHPSC\Conference\Domain\Entity\Event;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class EventFixture extends BaseFixture implements DependentFixture
{
    /**
     * {@inheritdoc}
     */
    public function import()
    {
        if ($this->findById(1)) {
            return ;
        }

        $event = new Event();
        $event->setId(1);
        $event->setLocation($this->findLocation());
        $event->setName('Evento de exemplo');
        $event->setStartDate(DateTime::createFromFormat('H:i:s', '00:00:00'));
        $event->setEndDate(DateTime::createFromFormat('H:i:s', '23:59:59'));
        $event->setSubmissionStart(DateTime::createFromFormat('H:i:s', '00:00:00'));
        $event->setSubmissionEnd(DateTime::createFromFormat('H:i:s', '23:59:59'));

        $event->getEndDate()->modify('+30 days');
        $event->getSubmissionEnd()->modify('+30 days');

        $this->forceAssignedIds($event);
        $this->manager->persist($event);
        $this->manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
        if ($location = $this->findById(1)) {
            $this->manager->remove($location);
            $this->manager->flush();
        }
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Location
     */
    protected function findLocation()
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\Location')->findOneById(1);
    }

    /**
     * @param int $id
     * @return Event
     */
    protected function findById($id)
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\Event')->findOneById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencyList()
    {
        return ['PHPSC\Conference\Infra\Fixtures\LocationFixture'];
    }
}
