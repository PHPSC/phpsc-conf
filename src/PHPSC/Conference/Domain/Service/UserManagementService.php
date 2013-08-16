<?php
namespace PHPSC\Conference\Domain\Service;

use \Abraham\TwitterOAuth\TwitterClient;
use \PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Domain\Repository\UserRepository;

class UserManagementService
{
    /**
     * @var \PHPSC\Conference\Domain\Repository\UserRepository
     */
    private $repository;

    /**
     * @var \Abraham\TwitterOAuth\TwitterClient
     */
    private $client;

    /**
     * @param \PHPSC\Conference\Domain\Repository\UserRepository $repository
     * @param \Abraham\TwitterOAuth\TwitterClient $client
     */
    public function __construct(
        UserRepository $repository,
        TwitterClient $client
    ) {
        $this->repository = $repository;
        $this->client = $client;
    }

    /**
     * @param string $name
     * @param string $twitterUser
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @param boolean $follow
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function create(
        $name,
        $twitterUser,
        $email,
        $githubUser,
        $bio,
        $follow
    ) {
        $user = User::create(
            $name,
            $twitterUser,
            $email,
            $githubUser,
            $bio
        );

        $this->repository->append($user);

        if ($follow) {
            $this->client->createFriendship('PHP_SC');
        }

        return $user;
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function update(
        $id,
        $name,
        $email,
        $githubUser,
        $bio
    ) {
        $user = $this->getById($id);
        $user->setName($name);
        $user->setEmail($email);
        $user->setGithubUser($githubUser);
        $user->setBio($bio);

        $this->repository->update($user);

        return $user;
    }

    /**
     * @param int $id
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getById($id)
    {
        return $this->repository->findOneById($id);
    }

    /**
     * @param string $twitterUser
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getByTwitterUser($twitterUser)
    {
        return $this->repository->findOneByTwitterUser($twitterUser);
    }
}
