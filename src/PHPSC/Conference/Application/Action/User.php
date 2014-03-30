<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\User as UserEntity;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\User\Form;
use PHPSC\Conference\Infra\UI\Component;

class User extends Controller
{
    /**
     * @Route("/new")
     */
    public function createUserForm()
    {
        $oauthData = $this->request->getSession()->get('oauth2.data');

        $user = new UserEntity();
        $user->setName($oauthData['user']->getName());

        return $this->showForm($user);
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
            $this->request->getSession()->get('oauth2.data'),
            $this->request->request->get('name'),
            $this->request->request->get('email'),
            $this->request->request->get('bio'),
            $redirectTo ?: '/'
        );
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function editForm()
    {
        return $this->showForm(Component::get('user'));
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
            $this->request->request->get('bio')
        );
    }

    /**
     * @param UserEntity $user
     * @return Main
     */
    protected function showForm(UserEntity $user)
    {
        return new Main(new Form($user));
    }

    /**
     * @return \PHPSC\Conference\Application\Service\UserJsonService
     */
    protected function getUserJsonService()
    {
        return $this->get('user.json.service');
    }
}
