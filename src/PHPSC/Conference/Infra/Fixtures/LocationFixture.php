<?php
namespace PHPSC\Conference\Infra\Fixtures;

use PHPSC\Conference\Domain\Entity\Location;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class LocationFixture extends BaseFixture
{
	/**
	 * {@inheritdoc}
     */
    public function import()
    {
        if ($this->findById(1)) {
            return ;
        }

        $location = new Location();
        $location->setId(1);
        $location->setName('Triângulo das bermudas');
        $location->setDescription('Local de exemplo do sistema de eventos');
        $location->setLatitude(25.00407);
        $location->setLongitude(71.00071);

        $this->forceAssignedIds($location);
        $this->manager->persist($location);
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
     * @param int $id
     * @return Location
     */
    protected function findById($id)
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\Location')->findOneById($id);
    }
}
