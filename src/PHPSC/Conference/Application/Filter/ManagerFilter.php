<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Errors\ForbiddenException;
use PHPSC\Conference\Domain\Service\EventManagementService;

class ManagerFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     *
     * @throws ForbiddenException
     */
    public function process()
    {
        $this->validateUserAuthentication();

        $user = $this->getAuthenticationService()->getLoggedUser();
        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$user->hasManagementPrivilegesOn($event)) {
            throw new ForbiddenException(
                'Você deve ter privilégios de gerenciamento para acessar esta página'
            );
        }
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }
}
