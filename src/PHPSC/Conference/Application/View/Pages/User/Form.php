<?php
namespace PHPSC\Conference\Application\View\Pages\User;

use PHPSC\Conference\Application\View\Main;

use \PHPSC\Conference\Domain\Entity\User;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Form extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\User
     */
    protected $user;

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        if ($this->isFirstAccess()) {
            Main::appendScript($this->getBaseUrl() . '/js/user.create.js');
        } else {
            Main::appendScript($this->getBaseUrl() . '/js/user.edit.js');
        }
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        $name = $this->getUser()->getName();
        $name = substr($name, 0, strpos($name, ' '));

        return $name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return !$this->isFirstAccess()
               ? 'Editando meus dados'
               : 'Seja bem vindo(a) ' . $this->getFirstName() . '!';
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        $base = $this->getBaseUrl();

        return !$this->isFirstAccess()
               ? $base . '/user/' . $this->user->getId()
               : $base . '/user/';
    }

    public function isFirstAccess()
    {
        return $this->user->getId() == 0;
    }
}