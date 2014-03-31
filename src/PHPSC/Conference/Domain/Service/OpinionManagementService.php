<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Opinion;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Repository\OpinionRepository;

class OpinionManagementService
{
    /**
     * @var OpinionRepository
     */
    private $repository;

    /**
     * @param OpinionRepository $repository
     */
    public function __construct(OpinionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return number
     */
    public function getLikesCount(Event $event, User $user)
    {
        return $this->repository->findNumberOfLikesFor($event, $user);
    }

    /**
     * @param Talk $talk
     * @return array
     */
    public function getByTalk(Talk $talk)
    {
        return $this->repository->findByTalk($talk);
    }

    /**
     * @param User $user
     * @param Talk $talk
     * @param boolean $likes
     * @return Opinion
     */
    public function create(User $user, Talk $talk, $likes)
    {
        $opinion = Opinion::create($user, $talk, $likes);

        $this->repository->append($opinion);

        return $opinion;
    }
}
