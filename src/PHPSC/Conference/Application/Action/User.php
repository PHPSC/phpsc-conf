<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\Conference\UI\Pages\User\Form;
use \PHPSC\Conference\UI\Main;
use \PHPSC\Conference\Domain\Entity\User as UserEntity;

use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;

class User extends Controller
{
    /**
     * @Route("/new")
     */
    public function createUserForm()
    {
        return $this->showForm(
            UserEntity::createFromTwitterData(
                $this->getTwitterProvider()->getLoggedUser()
            )
        );
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function newUser()
    {
        if ($redirectTo = $this->request->getSession()->get('redirectTo')) {
            $this->request->getSession()->remove('redirectTo');
        }

        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getUserJsonService()->create(
            $this->request->request->get('name'),
            $this->request->request->get('email'),
            $this->request->request->get('githubUser'),
            $this->request->request->get('bio'),
            $redirectTo ?: '/'
        );
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function editForm()
    {
        return $this->showForm(
            $this->getAuthenticationService()->getLoggedUser()
        );
    }

    /**
     * @Route("/(id)", methods={"POST"}, requirements={"[\d]{1,}"})
     * @param int $id
     */
    public function updateUser($id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getUserJsonService()->update(
            $id,
            $this->request->request->get('name'),
            $this->request->request->get('email'),
            $this->request->request->get('githubUser'),
            $this->request->request->get('bio')
        );
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\UI\Main
     */
    protected function showForm(\PHPSC\Conference\Domain\Entity\User $user)
    {
        return Main::create(new Form($user), $this->application);
    }

    /**
     * @return \PHPSC\Conference\Application\Service\TwitterAccessProvider
     */
    protected function getTwitterProvider()
    {
        return $this->get('twitter.provider');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\UserJsonService
     */
    protected function getUserJsonService()
    {
        return $this->get('user.json.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->application->getDependencyContainer()->get('authentication.service');
    }
}
