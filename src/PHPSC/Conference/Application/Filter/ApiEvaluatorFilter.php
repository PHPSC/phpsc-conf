<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Errors\ForbiddenException;
use Lcobucci\ActionMapper2\Errors\UnauthorizedException;
use PHPSC\Conference\Domain\Service\EventManagementService;

class ApiEvaluatorFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     *
     * @throws ForbiddenException
     */
    public function process()
    {
        $user = $this->getAuthenticationService()->getLoggedUser();

        if ($user === null) {
            throw new UnauthorizedException(
                'Você deve estar logado para acessar este recurso'
            );
        }

        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$event->isEvaluator($user)) {
            throw new ForbiddenException(
                'Você deve ser um avaliador para acessar este recurso'
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
