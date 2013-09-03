<?php
namespace PHPSC\Conference\Domain\Service;

use Lcobucci\Social\OAuth2\User as OAuth2User;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Entity\SocialProfile;
use PHPSC\Conference\Domain\Repository\SocialProfileRepository;
use PHPSC\Conference\Domain\Repository\UserRepository;
use PHPSC\Conference\Infra\Email\DeliveryService;

class UserManagementService
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var SocialProfileRepository
     */
    private $profileRepository;

    /**
     * @var DeliveryService
     */
    protected $deliveryService;

    /**
     * @param UserRepository $repository
     * @param SocialProfileRepository $profileRepository
     * @param DeliveryService $deliveryService
     */
    public function __construct(
        UserRepository $repository,
        SocialProfileRepository $profileRepository,
        DeliveryService $deliveryService
    ) {
        $this->repository = $repository;
        $this->profileRepository = $profileRepository;
        $this->deliveryService = $deliveryService;
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @return User
     */
    public function update(
        $id,
        $name,
        $email,
        $bio
    ) {
        $user = $this->getById($id);
        $user->setName($name);
        $user->setEmail($email);
        $user->setBio($bio);

        $this->repository->update($user);

        return $user;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById($id)
    {
        return $this->repository->findOneById($id);
    }

    /**
     * @param string $provider
     * @param OAuth2User $oauthUser
     * @param string $name
     * @param string $email
     * @param string $bio
     * @return User
     */
    public function create(
        $provider,
        OAuth2User $oauthUser,
        $name = null,
        $email = null,
        $bio = null
    ) {
        if (($email === null && $oauthUser->getEmail() == '')
            || ($name === null && $oauthUser->getName() == '')) {
            return ;
        }

        $user = User::create(
            $name ?: $oauthUser->getName(),
            $email ?: $oauthUser->getEmail(),
            $bio
        );

        $user->addProfile(SocialProfile::create($provider, $oauthUser, true));
        $this->repository->append($user);

        $message = $this->deliveryService->getMessageFromTemplate(
            'Welcome',
            array('name' => $user->getName())
        );

        $message->setTo($user->getEmail());

        $this->deliveryService->send($message);

        return $user;
    }

    /**
     * @param string $provider
     * @param OAuth2User $user
     * @return User
     */
    public function getByOAuthUser($provider, OAuth2User $oauthUser)
    {
        $profile = $this->profileRepository->findOneBySocialId(
            $provider,
            $oauthUser->getId()
        );

        if ($profile) {
            $profile->setAvatar($oauthUser->getAvatar());
            $this->profileRepository->update($profile);

            return $profile->getUser();
        }

        if ($oauthUser->getEmail() == '') {
            return null;
        }

        if ($user = $this->repository->findOneByEmail($oauthUser->getEmail())) {
            $user->addProfile(
                SocialProfile::create(
                    $provider,
                    $oauthUser,
                    !$user->hasAnyProfile()
                )
            );

            $this->repository->update($user);

            return $user;
        }
    }
}
