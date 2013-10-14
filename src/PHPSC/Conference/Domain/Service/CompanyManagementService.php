<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Domain\Factory\CompanyFactory;
use PHPSC\Conference\Domain\Repository\CompanyRepository;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class CompanyManagementService
{
    /**
     * @var CompanyRepository
     */
    private $repository;

    /**
     * @var CompanyFactory
     */
    private $factory;

    /**
     * @param CompanyRepository $repository
     * @param CompanyFactory $factory
     */
    public function __construct(
        CompanyRepository $repository,
        CompanyFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param string $name
     * @param string $socialId
     * @return array
     */
    public function search($name, $socialId)
    {
        if ($name !== null) {
            $name = filter_var($name, FILTER_SANITIZE_STRING);
        }

        if ($socialId !== null) {
            $socialId = filter_var($socialId, FILTER_SANITIZE_STRING);
            $socialId = preg_replace('/[^0-9]/', '', $socialId);
        }

        return $this->repository->search($name, $socialId);
    }

    /**
     * @param string $socialId
     * @return Company
     */
    public function findBySocialId($socialId)
    {
        $socialId = preg_replace('/[^0-9]/', '', $socialId);

        return $this->repository->findOneBySocialId($socialId);
    }

    /**
     * @param string $socialId
     * @param string $name
     * @param string $logo
     * @param string $email
     * @param string $phone
     * @param string $website
     * @param string $twitterId
     * @param string $fanpage
     */
    public function create(
        $socialId,
        $name,
        $logo,
        $email,
        $phone,
        $website,
        $twitterId,
        $fanpage
    ) {
        $company = $this->factory->create(
            $socialId,
            $name,
            $logo,
            $email,
            $phone,
            $website,
            $twitterId,
            $fanpage
        );

        $this->repository->append($company);

        return $company;
    }
}
