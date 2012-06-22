<?php
namespace Lcobucci\Common\Date;

use \DateTime;

class DateInterval
{
    /**
     * @var \DateTime
     */
    private $begin;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     */
    public function __construct(DateTime $begin, DateTime $end)
    {
        $this->setBegin($begin);
        $this->setEnd($end);
    }

	/**
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

	/**
     * @param \DateTime $begin
     */
    public function setBegin(DateTime $begin)
    {
        $this->begin = $begin;
    }

	/**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

	/**
     * @param \DateTime $end
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return \DateInterval
     */
    public function getDiff()
    {
        return $this->getBegin()->diff($this->getEnd());
    }
}