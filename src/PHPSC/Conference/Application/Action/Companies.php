<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Application\Service\CompanyJsonService;

class Companies extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function jsonSearch()
    {
        return $this->getCompanyJsonService()->search(
            $this->request->query->get('name'),
            $this->request->query->get('socialId')
        );
    }

    /**
     * @return CompanyJsonService
     */
    protected function getCompanyJsonService()
    {
        return $this->get('company.json.service');
    }
}
