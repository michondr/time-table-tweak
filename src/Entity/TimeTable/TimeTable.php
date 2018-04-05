<?php

namespace App\Entity\TimeTable;

use App\Entity\Location\Location;
use App\Entity\Subject\Subject;
use App\Entity\Teacher\Teacher;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class TimeTable
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject\Subject")
     * @ORM\JoinColumn(name="subject", referencedColumnName="id")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher\Teacher")
     * @ORM\JoinColumn(name="teacher", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location\Location")
     * @ORM\JoinColumn(name="location", referencedColumnName="id")
     */
    private $location;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Subject|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher($teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Location|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * @param mixed $actionType
     */
    public function setActionType($actionType): void
    {
        $this->actionType = $actionType;
    }

    /**
     * @return int
     */
    public function getCapacityFull()
    {
        return $this->capacityFull;
    }

    /**
     * @param mixed $capacityFull
     */
    public function setCapacityFull($capacityFull): void
    {
        $this->capacityFull = $capacityFull;
    }

    /**
     * @return int
     */
    public function getCapacityClass1()
    {
        return $this->capacityClass1;
    }

    /**
     * @param mixed $capacityClass1
     */
    public function setCapacityClass1($capacityClass1): void
    {
        $this->capacityClass1 = $capacityClass1;
    }

    /**
     * @return int
     */
    public function getCapacityClass2()
    {
        return $this->capacityClass2;
    }

    /**
     * @param mixed $capacityClass2
     */
    public function setCapacityClass2($capacityClass2): void
    {
        $this->capacityClass2 = $capacityClass2;
    }

    /**
     * @return int
     */
    public function getCapacityClass3()
    {
        return $this->capacityClass3;
    }

    /**
     * @param mixed $capacityClass3
     */
    public function setCapacityClass3($capacityClass3): void
    {
        $this->capacityClass3 = $capacityClass3;
    }
}