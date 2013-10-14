<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Errors\ForbiddenException;
use Lcobucci\ActionMapper2\Errors\UnauthorizedException;

class ApiManagementFilter extends BasicFilter
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

        if (!$user->isAdmin()) {
            throw new ForbiddenException(
                'Você deve ser um administrador para acessar este recurso'
            );
        }
    }
}
