<?php

namespace PHPSC\Conference\Domain\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use PHPSC\Conference\Domain\Entity\TalkType as TalkTypeEntity;

class TalkTypeData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $minicurso = new TalkTypeEntity();
        $minicurso->setDescription('Minicurso');
        $minicurso->setLength(\DateTime::createFromFormat('H:i', '4:00'));
        $manager->persist($minicurso);

        $palestra = new TalkTypeEntity();
        $palestra->setDescription('Palestra');
        $palestra->setLength(\DateTime::createFromFormat('H:i', '1:00'));
        $manager->persist($palestra);

        $palestraCurta = new TalkTypeEntity();
        $palestraCurta->setDescription('Palestra Curta');
        $palestraCurta->setLength(\DateTime::createFromFormat('H:i', '0:20'));
        $manager->persist($palestraCurta);

        $mesaRedonda = new TalkTypeEntity();
        $mesaRedonda->setDescription('Mesa Redonda');
        $mesaRedonda->setLength(\DateTime::createFromFormat('H:i', '1:00'));
        $manager->persist($mesaRedonda);

        $manager->flush();
    }

}
