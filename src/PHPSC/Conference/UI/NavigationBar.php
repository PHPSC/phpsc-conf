<?php
namespace PHPSC\Conference\UI;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\SocialProfile;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Application\Service\AuthenticationService;

class NavigationBar extends UIComponent
{
    /**
     * @var EventManagementService
     */
    protected $eventService;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @param EventManagementService $eventService
     * @param AuthenticationService $authService
     */
    public function __construct(
        EventManagementService $eventService,
        AuthenticationService $authService
    ) {
        $this->eventService = $eventService;
        $this->authService = $authService;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $event = $this->eventService->findCurrentEvent();

        $items = array(
            array('Página Inicial', ''),
            array('Sobre o Evento', 'about'),
            array('O Local', 'venue'),
            array('Inscrições', 'registration')
        );

        if ($event->isSubmissionsInterval(new \DateTime())) {
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
        if ($user = $this->authService->getLoggedUser()) {
            return $user->getDefaultProfile();
        }
    }

    /**
     * @return MainMenu
     */
    protected function getMenu()
    {
        return new MainMenu($this->authService->getLoggedUser());
    }
}
