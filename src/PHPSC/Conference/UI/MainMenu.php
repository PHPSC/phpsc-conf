<?php
namespace PHPSC\Conference\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class MainMenu extends UIComponent
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    protected function getItems()
    {
        if ($this->user === null) {
            return $this->getUnloggedUserItems();
        }

        return $this->getLoggedUserItems();
    }

    /**
     * @return array
     */
    protected function getLoggedUserItems()
    {
        $items = array(
            'Meus dados' => $this->getUrl('user'),
            'Trabalhos submetidos' => $this->getUrl('call4papers/submissions')
        );

        if ($this->user->isAdmin()) {
            $items[] = null;
            $items['Gerenciar apoiadores'] = $this->getUrl('adm/supporters');
        }

        $items[] = null;
        $items['Sair'] = $this->getUrl('logoff');

        return $items;
    }

    /**
     * @return array
     */
    protected function getUnloggedUserItems()
    {
        return array(
            'Github' => $this->getUrl('oauth/github'),
            'Facebook' => $this->getUrl('oauth/facebook'),
            'Google' => $this->getUrl('oauth/google'),
            'LinkedIn' => $this->getUrl('oauth/linkedin'),
            'Microsoft Live' => $this->getUrl('oauth/live'),
        );
    }
}
