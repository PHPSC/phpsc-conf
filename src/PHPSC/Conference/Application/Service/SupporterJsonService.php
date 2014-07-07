<?php
namespace PHPSC\Conference\Application\Service;

use InvalidArgumentException;
use Lcobucci\ActionMapper2\Errors\BadRequestException;
use PDOException;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Supporter;
use PHPSC\Conference\Domain\Service\CompanyManagementService;
use PHPSC\Conference\Domain\Service\SupporterManagementService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\DBAL\DBALException;
use Lcobucci\ActionMapper2\Errors\ConflictException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class SupporterJsonService
{
    /**
     * @var SupporterManagementService
     */
    private $supporterManager;

    /**
     * @var CompanyManagementService
     */
    private $companyManager;

    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * @param SupporterManagementService $supporterManager
     * @param CompanyManagementService $companyManager
     * @param AuthenticationService $authService
     */
    public function __construct(
        SupporterManagementService $supporterManager,
        CompanyManagementService $companyManager,
        AuthenticationService $authService
    ) {
        $this->supporterManager = $supporterManager;
        $this->companyManager = $companyManager;
        $this->authService = $authService;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        $supporters = array();

        foreach ($this->supporterManager->findByEvent($event) as $supporter) {
            $supporters[] = $this->toArray($supporter);
        }

        return $supporters;
    }

    /**
     * @param Event $event
     * @param string $socialId
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $website
     * @param string $twitterId
     * @param string $fanpage
     * @param string $details
     * @param UploadedFile $logo
     * @throws BadRequestException
     * @return string
     */
    public function create(
        Event $event,
        $socialId,
        $name,
        $email,
        $phone,
        $website,
        $twitterId,
        $fanpage,
        $details,
        UploadedFile $logo = null
    ) {
        try {
            $supporter = $this->supporterManager->create(
                $event,
                $this->createCompanyIfNeeded(
                    $socialId,
                    $name,
                    $email,
                    $phone,
                    $website,
                    $twitterId,
                    $fanpage,
                    $logo
                ),
                filter_var($details, FILTER_SANITIZE_STRING)
            );

            return $this->toArray($supporter);
        } catch (InvalidArgumentException $error) {
            throw new BadRequestException($error->getMessage(), null, $error);
        } catch (DBALException $error) {
            if (strpos($error->getMessage(), 'Duplicate entry') !== false) {
                throw new ConflictException('Esta empresa já apoia o evento', null, $error);
            }

            throw $error;
        }
    }

    /**
     * @param string $socialId
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $website
     * @param string $twitterId
     * @param string $fanpage
     * @param UploadedFile $logo
     * @return Company
     */
    protected function createCompanyIfNeeded(
        $socialId,
        $name,
        $email,
        $phone,
        $website,
        $twitterId,
        $fanpage,
        UploadedFile $logo = null
    ) {
        if ($company = $this->companyManager->findBySocialId($socialId)) {
            return $company;
        }

        return $this->companyManager->create(
            filter_var($socialId, FILTER_SANITIZE_STRING),
            filter_var($name, FILTER_SANITIZE_STRING),
            $logo->getRealPath(),
            filter_var($email, FILTER_SANITIZE_EMAIL),
            empty($phone) ? null : filter_var($phone, FILTER_SANITIZE_STRING),
            filter_var($website, FILTER_SANITIZE_URL),
            empty($twitterId) ? null : filter_var($twitterId, FILTER_SANITIZE_STRING),
            empty($fanpage) ? null : filter_var($fanpage, FILTER_SANITIZE_URL)
        );
    }

    /**
     * @param Supporter $supporter
     * @return array
     */
    protected function toArray(Supporter $supporter)
    {
        if (!$this->isAdmin()) {
            return array(
                'id' => $supporter->getId(),
                'name' => $supporter->getCompany()->getName(),
                'website' => $supporter->getCompany()->getWebsite()
            );
        }

        return array(
            'id' => $supporter->getId(),
            'socialId' => $supporter->getCompany()->getSocialId(),
            'name' => $supporter->getCompany()->getName(),
            'email' => $supporter->getCompany()->getEmail(),
            'phone' => $supporter->getCompany()->getPhone(),
            'website' => $supporter->getCompany()->getWebsite(),
            'twitterId' => $supporter->getCompany()->getTwitterId(),
            'fanpage' => $supporter->getCompany()->getFanpage()
        );
    }

    /**
     * @return boolean
     */
    private function isAdmin()
    {
        $user = $this->authService->getLoggedUser();

        return $user !== null && $user->isAdmin();
    }
}
