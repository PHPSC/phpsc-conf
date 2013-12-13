<?php

namespace PHPSC\Conference\Domain\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use PHPSC\Conference\Domain\Entity\RegistrationInfo as RegistrationInfoEntity;
use DateTime;

class RegistrationInfoData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $event = $this->getReference('event');

        $registrationInfo = new RegistrationInfoEntity();
        $registrationInfo->setEvent($event);
        $registrationInfo->setStart(DateTime::createFromFormat('Y-m-d H:i:s', '2012-08-06 00:00:00'));
        $registrationInfo->setEnd(DateTime::createFromFormat('Y-m-d H:i:s', '2012-10-26 23:59:59'));
        $registrationInfo->setRegularPrice(15);
        $registrationInfo->setEarlyPrice(10);
        $registrationInfo->setStudentLabel('Sou estudante da UNIVALI');
        $registrationInfo->setStudentRules('<p>Estudantes da UNIVALI (Universidade do Vale do Itajaí) serão contemplados com <strong>100%</strong> de desconto <strong>no valor da inscrição do evento</strong>.</p>\n<p>Será <strong>obrigatória</strong> a apresentação do comprovante de matrícula no credenciamento, no início do evento.</p>\n<p><strong>Importante:</strong> as pessoas que se inscreverem como alunos da UNIVALI e não apresentarem o comprovante de matrícula deverão pagar, no credenciamento, o valor de <strong>R$ 20,00</strong> (correspondente à inscrições realizadas no dia do evento).</p>');

        $manager->persist($registrationInfo);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}
