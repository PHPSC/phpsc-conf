<?php
namespace PHPSC\Conference\Domain\Factory;

use DateTime;
use ImagickException;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Images\ImageFactory;
use PHPSC\Conference\Infra\Images\ImageValidator;
use PHPSC\Conference\Domain\Entity\Company;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class CompanyFactory
{
    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @var ImageValidator
     */
    private $imageValidator;

    /**
     * @param ImageFactory $imageFactory
     * @param ImageValidator $imageValidator
     */
    public function __construct(
        ImageFactory $imageFactory,
        ImageValidator $imageValidator
    ) {
        $this->imageFactory = $imageFactory;
        $this->imageValidator = $imageValidator;
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
     * @return Company
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
        $company = new Company();
        $company->setSocialId($socialId);
        $company->setName($name);
        $company->setLogo($this->createResoure($logo));
        $company->setEmail($email);
        $company->setPhone($phone);
        $company->setWebsite($website);
        $company->setTwitterId($twitterId);
        $company->setFanpage($fanpage);
        $company->setCreationTime(new DateTime());

        return $company;
    }

    /**
     * @param string $filename
     * @throws InvalidArgumentException
     * @return resource
     */
    protected function createResource($filename)
    {
        try {
            $this->validateImage($filename);

            return fopen($filename, 'rb');
        } catch (ImagickException $error) {
            throw new InvalidArgumentException(
                'O logotipo deve ser um PNG com fundo transparente'
            );
        }
    }

    /**
     * @param string $filename
     * @throws InvalidArgumentException
     */
    protected function validateImage($filename)
    {
        $image = $this->imageFactory->createFromFile($filename);

        if (!$this->imageValidator->isTransparentPng($image)) {
            throw new InvalidArgumentException(
                'O logotipo deve ser um PNG com fundo transparente'
            );
        }
    }
}
