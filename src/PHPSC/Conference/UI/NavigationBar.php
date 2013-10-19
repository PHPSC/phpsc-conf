<?php
namespace PHPSC\Conference\UI;

use DateTime;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\SocialProfile;
use PHPSC\Conference\Domain\Entity\User;

class NavigationBar extends UIComponent
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var User
     */
    protected $loggedUser;

    /**
     * @param Event $event
     * @param User $loggedUser
     */
    public function __construct(Event $event, User $loggedUser = null)
    {
        $this->event = $event;
        $this->loggedUser = $loggedUser;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $items = array(
            array('Página Inicial', ''),
            array('Sobre o Evento', 'about'),
            array('O Local', 'venue'),
            array('Inscrições', 'registration')
        );

        if ($this->event->isSubmissionsInterval(new DateTime())) {
            $items[] = array('Chamada de Trabalhos', 'call4papers');
        } else {
            $items[] = array('Grade de Palestras', 'talks');
        }

        $items[] = array('Contato', 'contact');



        return $items;
    }

    /**
     * @return SocialProfile
     */
    public function getProfile()
    {
        if ($this->loggedUser) {
            return $this->loggedUser->getDefaultProfile();
        }
    }

    /**
     * @return MainMenu
     */
    protected function getMenu()
    {
        return new MainMenu($this->event, $this->loggedUser);
    }
}
