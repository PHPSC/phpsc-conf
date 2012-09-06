<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Entity\Opinion;

use PHPSC\Conference\Domain\Entity\Talk;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Domain\Repository\OpinionRepository;

class OpinionManagementService
{
    /**
     * @var \PHPSC\Conference\Domain\Repository\OpinionRepository
     */
    private $repository;

    /**
     * @param \PHPSC\Conference\Domain\Repository\OpinionRepository $repository
     */
    public function __construct(OpinionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return number
     */
    public function getLikesCount(Event $event, User $user)
    {
        return $this->repository->findNumberOfLikesFor($event, $user);
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Talk $talk
     * @param boolean $likes
     * @return \PHPSC\Conference\Domain\Entity\Opinion
     */
    public function create(User $user, Talk $talk, $likes)
    {
        $opinion = Opinion::create($user, $talk, $likes);

        $this->repository->append($opinion);

        return $opinion;
    }
}