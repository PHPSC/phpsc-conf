<?php
namespace PHPSC\Conference\Application\Service;

use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Domain\Service\CompanyManagementService;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class CompanyJsonService
{
    /**
     * @var CompanyManagementService
     */
    private $managementService;

    /**
     * @param CompanyManagementService $managementService
     */
    public function __construct(CompanyManagementService $managementService)
    {
        $this->managementService = $managementService;
    }

    /**
     * @param string $name
     * @param string $socialId
     * @return array
     */
    public function search($name, $socialId)
    {
        $companies = array();

        foreach ($this->managementService->search($name, $socialId) as $company) {
            $companies[] = $this->toJson($company);
        }

        return json_encode($companies);
    }

    /**
     * @param Company $company
     * @return array
     */
    protected function toJson(Company $company)
    {
        return array(
        	'id' => $company->getId(),
            'socialId' => $company->getSocialId(),
            'name' => $company->getName(),
            'email' => $company->getEmail(),
            'phone' => $company->getPhone(),
            'website' => $company->getWebsite(),
            'twitterId' => $company->getTwitterId(),
            'fanpage' => $company->getFanpage()
        );
    }
}
