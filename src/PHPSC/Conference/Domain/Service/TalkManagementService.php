<?php
namespace PHPSC\Conference\Domain\Service;

use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\Talk;
use \PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Domain\Repository\TalkTypeRepository;
use \PHPSC\Conference\Domain\Repository\TalkRepository;
use \RuntimeException;

class TalkManagementService
{
    /**
     * @var \PHPSC\Conference\Domain\Repository\TalkRepository
     */
    private $talkRepository;

    /**
     * @var \PHPSC\Conference\Domain\Repository\TalkTypeRepository
     */
    private $talkTypeRepository;

    /**
     * @param \PHPSC\Conference\Domain\Repository\TalkRepository $talkRepository
     */
    public function __construct(
        TalkRepository $talkRepository,
        TalkTypeRepository $talkTypeRepository
    ) {
        $this->talkRepository = $talkRepository;
        $this->talkTypeRepository = $talkTypeRepository;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $speaker
     * @param int $typeId
     * @param string $title
     * @param string $shortDescription
     * @param string $longDescription
     * @param string $complexity
     * @param string $tags
     * @return \PHPSC\Conference\Domain\Entity\Talk
     */
    public function create(
        Event $event,
        User $speaker,
        $typeId,
        $title,
        $shortDescription,
        $longDescription,
        $complexity,
        $tags
    ) {
        $talk = Talk::create(
            $event,
            $speaker,
            $this->talkTypeRepository->findOneById($typeId),
            $title,
            $shortDescription,
            $longDescription,
            $complexity,
            $tags
        );

        $this->talkRepository->append($talk);

        return $talk;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @return \PHPSC\Conference\Domain\Entity\Talk[]
     */
    public function findByUserAndEvent(User $user, Event $event)
    {
    	return $this->talkRepository->findByUserAndEvent($user, $event);
    }
}