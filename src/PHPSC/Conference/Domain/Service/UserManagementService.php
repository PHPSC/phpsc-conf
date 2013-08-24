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
     * @param \PHPSC\Conference\Domain\Repository\UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $name
     * @param string $twitterUser
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function create(
        $name,
        $twitterUser,
        $email,
        $githubUser,
        $bio
    ) {
        $user = User::create(
            $name,
            $twitterUser,
            $email,
            $githubUser,
            $bio
        );

        $this->repository->append($user);

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
