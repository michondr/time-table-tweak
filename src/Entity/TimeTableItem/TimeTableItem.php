<?php

namespace App\Entity\TimeTableItem;

use App\DateTime\Time\Time;
use App\Entity\EntityFieldManager;
use App\Entity\Location\Location;
use App\Entity\Subject\Subject;
use App\Entity\Teacher\Teacher;
use App\TimeTableBuilder\TimeTable;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="App\Entity\TimeTableItem\TimeTableItemRepository") */
class TimeTableItem extends EntityFieldManager
{
    const ACTION_SEMINAR = 'seminar';
    const ACTION_LECTURE = 'lecture';
    const ACTION_OTHER = 'other';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject\Subject", cascade={"persist"})
     * @ORM\JoinColumn(name="subject", referencedColumnName="id")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher\Teacher", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="teacher", referencedColumnName="id", nullable=true)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location\Location", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="location", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Column(type="michondr_time")
     */
    private $timeFrom;

    /**
     * @ORM\Column(type="michondr_time")
     */
    private $timeTo;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('lecture', 'seminar', 'other')")
     */
    private $actionType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacityFull;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacityClass1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacityClass2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacityClass3;

    public function getId()
    {
        return $this->id;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): void
    {
        $this->subject = $subject;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): void
    {
        $this->teacher = $teacher;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getActionType()
    {
        return $this->actionType;
    }

    public function setActionType($actionType): void
    {
        $this->actionType = $actionType;
    }

    public function getCapacityFull()
    {
        return $this->capacityFull;
    }

    public function setCapacityFull($capacityFull): void
    {
        $this->capacityFull = $capacityFull;
    }

    public function getCapacityClass1()
    {
        return $this->capacityClass1;
    }

    public function setCapacityClass1($capacityClass1): void
    {
        $this->capacityClass1 = $capacityClass1;
    }

    public function getCapacityClass2()
    {
        return $this->capacityClass2;
    }

    public function setCapacityClass2($capacityClass2): void
    {
        $this->capacityClass2 = $capacityClass2;
    }

    public function getCapacityClass3()
    {
        return $this->capacityClass3;
    }

    public function setCapacityClass3($capacityClass3): void
    {
        $this->capacityClass3 = $capacityClass3;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function setDay($day): void
    {
        $this->day = $day;
    }

    public function getTimeFrom(): ?Time
    {
        return $this->timeFrom;
    }

    public function setTimeFrom($timeFrom): void
    {
        $this->timeFrom = $timeFrom;
    }

    public function getTimeTo(): ?Time
    {
        return $this->timeTo;
    }

    public function setTimeTo($timeTo): void
    {
        $this->timeTo = $timeTo;
    }

    public function getTimeTableOccupiedIds()
    {
        $startingId = TimeTable::getIntervalIdByStartTime($this->getTimeFrom());
        $endingId = TimeTable::getIntervalIdByEndTime($this->getTimeTo());
        $ids = [];

        while ($startingId <= $endingId){
            $ids[] = $startingId;
            $startingId++;
        }

        return $ids;
    }
}
