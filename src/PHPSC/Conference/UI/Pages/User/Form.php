<?php
namespace PHPSC\Conference\UI\Pages\User;

use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Infra\UI\Component;

class Form extends Component
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
            Main::appendScript($this->getUrl('js/user.create.js'));
        } else {
            Main::appendScript($this->getUrl('js/user.edit.js'));
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
        return !$this->isFirstAccess()
               ? $this->getUrl('user/' . $this->user->getId())
               : $this->getUrl('user');
    }

    public function isFirstAccess()
    {
        return $this->user->getId() == 0;
    }
}
