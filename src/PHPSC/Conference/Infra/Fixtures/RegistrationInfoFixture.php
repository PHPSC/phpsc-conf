<?php
namespace PHPSC\Conference\Infra\Fixtures;

use DateTime;
use Doctrine\Fixture\Sorter\DependentFixture;
use PHPSC\Conference\Domain\Entity\RegistrationInfo;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class RegistrationInfoFixture extends BaseFixture implements DependentFixture
{
    /**
     * {@inheritdoc}
     */
    public function import()
    {
        if ($this->findById(1)) {
            return ;
        }

        $info = new RegistrationInfo();
        $info->setId(1);
        $info->setEvent($this->findEvent());
        $info->setStart(DateTime::createFromFormat('H:i:s', '00:00:00'));
        $info->setEnd(DateTime::createFromFormat('H:i:s', '23:59:59'));
        $info->setRegularPrice(15);
        $info->setEarlyPrice(10);
        $info->setStudentLabel('Sou estudante');
        $info->setStudentRules('<p>Instruções e regras para preços de estudantes</p>');

        $info->getEnd()->modify('+29 days');

        $this->forceAssignedIds($info);
        $this->manager->persist($info);
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
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    protected function findEvent()
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\Event')->findOneById(1);
    }

    /**
     * @param int $id
     * @return RegistrationInfo
     */
    protected function findById($id)
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\RegistrationInfo')->findOneById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencyList()
    {
        return ['PHPSC\Conference\Infra\Fixtures\EventFixture'];
    }
}
