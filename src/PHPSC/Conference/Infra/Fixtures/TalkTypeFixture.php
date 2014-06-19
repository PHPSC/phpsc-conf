<?php
namespace PHPSC\Conference\Infra\Fixtures;

use DateTime;
use PHPSC\Conference\Domain\Entity\TalkType;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkTypeFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function import()
    {
        $this->createType(1, 'Minicurso', '4:00');
        $this->createType(5, 'Minicurso Longo', '8:00');
        $this->createType(2, 'Palestra', '1:00');
        $this->createType(3, 'Palestra Curta', '0:20');
        $this->createType(4, 'Mesa Redonda', '1:00');

        $this->forceAssignedIds('PHPSC\Conference\Domain\Entity\TalkType');
        $this->manager->flush();
    }

    /**
     * @param string $description
     * @param string $length
     */
    protected function createType($id, $description, $length)
    {
        if ($this->findById($id)) {
            return ;
        }

        $type = new TalkType();
        $type->setId($id);
        $type->setDescription($description);
        $type->setLength(DateTime::createFromFormat('H:i', $length));

        $this->manager->persist($type);
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
        foreach (array(1, 2, 3, 4) as $id) {
            if ($type = $this->findById($id)) {
                $this->manager->remove($type);
            }
        }

        $this->manager->flush();
    }

    /**
     * @param int $id
     * @return TalkType
     */
    protected function findById($id)
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\TalkType')->findOneById($id);
    }
}
