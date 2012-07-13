<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\Conference\Application\View\Pages\User\Form;
use \PHPSC\Conference\Application\View\Main;
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
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getUserJsonService()->create(
            $this->request->request->get('name'),
            $this->request->request->get('email'),
            $this->request->request->get('githubUser'),
            $this->request->request->get('bio'),
            $this->request->request->get('follow') == 'true'
        );
    }

    /**
     * @Route("/(id)", methods={"POST"}, requirements={"[\d]{1,}"})
     * @param int $id
     */
    public function updateUser($id)
    {

    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\Application\View\Main
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
}