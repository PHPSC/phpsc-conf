<?php
namespace PHPSC\Conference\UI;

use DateTime;
use PHPSC\Conference\Domain\Entity\SocialProfile;
use PHPSC\Conference\Infra\UI\Component;

class NavigationBar extends Component
{
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

        if ($this->event->isSubmissionsPeriod(new DateTime())) {
            $items[] = array('Chamada de Trabalhos', 'call4papers');
        } else {
            $items[] = array('Programação', 'talks');
        }

        $items[] = array('Contato', 'contact');



        return $items;
    }

    /**
     * @return SocialProfile
     */
    public function getProfile()
    {
        if ($this->user) {
            return $this->user->getDefaultProfile();
        }
    }

    /**
     * @return MainMenu
     */
    protected function getMenu()
    {
        return new MainMenu();
    }
}
