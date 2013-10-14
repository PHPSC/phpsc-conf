<?php
namespace PHPSC\Conference\Domain\Factory;

use DateTime;
use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Supporter;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class SupporterFactory
{
    /**
     * @param Event $event
     * @param Company $company
     * @param string $details
     * @return Supporter
     */
    public function create(
        Event $event,
        Company $company,
        $details
    ) {
        $supporter = new Supporter();
        $supporter->setCompany($company);
        $supporter->setEvent($event);
        $supporter->setDetails($details);
        $supporter->setCreationTime(new DateTime());

        return $supporter;
    }
}
