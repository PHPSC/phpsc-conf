<?php
namespace PHPSC\Conference\Infra\Persistence;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param \PHPSC\Conference\Infra\Persistence\Entity $obj
     */
    public function append(Entity $obj)
    {
        if ($obj->getId() > 0) {
            throw new EntityAlreadyExistsException(
                'Não é possível criar uma entidade que já existe'
            );
        }

        $this->getEntityManager()->persist($obj);
        $this->getEntityManager()->flush();
    }

    /**
     * @param \PHPSC\Conference\Infra\Persistence\Entity $obj
     */
    public function update(Entity $obj)
    {
        if ($obj->getId() == 0) {
            throw new EntityDoesNotExistsException(
                'Não é possível atualizar uma entidade que ainda não foi adicionada'
            );
        }

        $this->getEntityManager()->persist($obj);
        $this->getEntityManager()->flush();
    }
}
