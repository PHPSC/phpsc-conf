<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\ScheduledItemRepository")
 * @Table(
 *      "scheduled_item",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="scheduled_item_index0",columns={"event_id", "room_id", "start_time"}),
 *      }
 * )
 * @author Luís Otávio Cobucci Oblonczyk
 */
class ScheduledItem
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Event")
     * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     * @var Event
     */
    private $event;

    /**
     * @ManyToOne(targetEntity="Room")
     * @JoinColumn(name="room_id", referencedColumnName="id")
     * @var Room
     */
    private $room;

    /**
     * @ManyToOne(targetEntity="Talk")
     * @JoinColumn(name="talk_id", referencedColumnName="id", nullable=true)
     * @var Talk
     */
    private $talk;

    /**
     * @Column(type="string", length=80)
     * @var string
     */
    private $label;

    /**
     * @Column(type="datetime", name="start_time")
     * @var DateTime
     */
    private $startTime;

    /**
     * @Column(type="boolean")
     * @var bool
     */
    private $active;

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
        $this->id = $id;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room = null)
    {
        $this->room = $room;
    }

    /**
     * @return Talk
     */
    public function getTalk()
    {
        return $this->talk;
    }

    /**
     * @param Talk $talk
     */
    public function setTalk(Talk $talk = null)
    {
        $this->talk = $talk;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime(DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = array(
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'startTime' => $this->getStartTime()->format(DateTime::ISO8601),
            'room' => null,
            'talk' => null
        );

        if ($this->getRoom()) {
            $data['room'] = $this->getRoom()->jsonSerialize();
        }

        if ($this->getTalk()) {
            $data['talk'] = array(
            	'id' => $this->getTalk()->getId(),
            	'title' => $this->getTalk()->getTitle(),
            	'speakers' => array(),
            	'cost' => $this->getTalk()->getCost()
            );

            foreach ($this->getTalk()->getSpeakers() as $speaker) {
                $data['talk']['speakers'][] = array(
                	'id' => $speaker->getId(),
                    'name' => $speaker->getName(),
                    'avatar' => $speaker->getDefaultProfile()->getAvatar()
                );
            }
        }

        return $data;
    }
}