<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Errors\ForbiddenException;

class AdministratorFilter extends BasicFilter
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

        if (!$user->isAdmin()) {
            throw new ForbiddenException(
                'Você deve ser um administrador para acessar esta página'
            );
        }
    }
}
