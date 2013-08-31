<?php
namespace PHPSC\Conference\Domain\Entity;

use \PHPSC\Conference\Domain\Service\TalkManagementService;
use \PHPSC\Conference\Infra\Persistence\Entity;
use \InvalidArgumentException;
use \DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\EventRepository")
 * @Table(
 *     "event",
 *     indexes={
 *         @Index(name="event_index0", columns={"start"}),
 *         @Index(name="evento_index1", columns={"submissions_start", "submissions_end"})
 *     }
 * )
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Event implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=80, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Location", cascade={"all"})
     * @JoinColumn(name="location_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\Location
     */
    private $location;

    /**
     * @OneToOne(targetEntity="RegistrationInfo", mappedBy="event")
     * @var \PHPSC\Conference\Domain\Entity\RegistrationInfo
     */
    private $registrationInfo;

    /**
     * @Column(type="date", nullable=false, name="start")
     * @var \DateTime
     */
    private $startDate;

    /**
     * @Column(type="date", nullable=false, name="end")
     * @var \DateTime
     */
    private $endDate;

    /**
     * @Column(type="datetime", name="submissions_start", nullable=true)
     * @var \DateTime
     */
    private $submissionStart;

    /**
     * @Column(type="datetime", name="submissions_end", nullable=true)
     * @var \DateTime
     */
    private $submissionEnd;

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'O id deve ser maior ou igual à ZERO'
            );
        }

        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('O nome não pode ser vazio');
        }

        $this->name = (string) $name;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\RegistrationInfo
     */
    public function getRegistrationInfo()
    {
        return $this->registrationInfo;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\RegistrationInfo $registrationInfo
     */
    public function setRegistrationInfo(RegistrationInfo $registrationInfo)
    {
        $this->registrationInfo = $registrationInfo;
    }

    /**
     * @return boolean
     */
    public function hasAttendeeRegistration()
    {
        return $this->getRegistrationInfo() !== null;
    }

    /**
     * @param \DateTime $date
     * @return boolean
     */
    public function isRegistrationInterval(DateTime $date)
    {
        if (!$this->hasAttendeeRegistration()) {
            return false;
        }

        return $date >= $this->getRegistrationInfo()->getStart()
               && $date <= $this->getRegistrationInfo()->getEnd();
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkService
     * @return float
     */
    public function getRegistrationCost(
        User $user,
        TalkManagementService $talkService
    ) {
        if (!$this->hasAttendeeRegistration()) {
            return 0;
        }

        if (!$this->getRegistrationInfo()->hasEarlyPrice()) {
            return $this->getRegistrationInfo()->getRegularPrice();
        }

        if ($talkService->userHasAnyTalk($user, $this)
            && $this->isSpeakerPromotionalInterval(new DateTime())) {
            return $this->getRegistrationInfo()->getEarlyPrice();
        }

        return $talkService->eventHasAnyApprovedTalk($this)
               ? $this->getRegistrationInfo()->getRegularPrice()
               : $this->getRegistrationInfo()->getEarlyPrice();
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return \DateTime
     */
    public function getSubmissionStart()
    {
        return $this->submissionStart;
    }

    /**
     * @param \DateTime $submissionStart
     */
    public function setSubmissionStart(DateTime $submissionStart = null)
    {
        $this->submissionStart = $submissionStart;
    }

    /**
     * @return \DateTime
     */
    public function getSubmissionEnd()
    {
        return $this->submissionEnd;
    }

    /**
     * @param \DateTime $submissionEnd
     */
    public function setSubmissionEnd(DateTime $submissionEnd = null)
    {
        $this->submissionEnd = $submissionEnd;
    }

    /**
     * @return boolean
     */
    public function hasTalkSubmissions()
    {
        return $this->getSubmissionStart() !== null;
    }

    /**
     * @return \DateTime
     */
    public function getTalkApprovalEnd()
    {
        if (!$this->hasTalkSubmissions()) {
            return null;
        }

        $approvalEnd = clone $this->getSubmissionEnd();
        $approvalEnd->modify('+7 days');

        return $approvalEnd;
    }

    /**
     * @param \DateTime $date
     * @return boolean
     */
    public function isSpeakerPromotionalInterval(DateTime $date)
    {
        if ($approvalEnd = $this->getTalkApprovalEnd()) {
            $approvalEnd->modify('+7 days');

            return $date <= $approvalEnd;
        }

        return false;
    }

    /**
     * @param \DateTime $date
     * @return boolean
     */
    public function isSubmissionsInterval(DateTime $date)
    {
        if (!$this->hasTalkSubmissions()) {
            return false;
        }

        return $date >= $this->getSubmissionStart()
               && $date <= $this->getSubmissionEnd();
    }
}
