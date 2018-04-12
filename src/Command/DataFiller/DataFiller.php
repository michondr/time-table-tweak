<?php

namespace App\Command\DataFiller;

use App\DateTime\DateTimeFactory;
use App\Entity\Location\Location;
use App\Entity\Location\LocationFacade;
use App\Entity\Subject\Subject;
use App\Entity\Subject\SubjectFacade;
use App\Entity\Teacher\Teacher;
use App\Entity\Teacher\TeacherFacade;
use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;

class DataFiller
{

    /**
     * @var TeacherFacade
     */
    private $teacherFacade;
    /**
     * @var SubjectFacade
     */
    private $subjectFacade;
    /**
     * @var LocationFacade
     */
    private $locationFacade;
    /**
     * @var TimeTableItemFacade
     */
    private $timeTableFacade;
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;

    public function __construct(
        TeacherFacade $teacherFacade,
        SubjectFacade $subjectFacade,
        LocationFacade $locationFacade,
        TimeTableItemFacade $timeTableFacade,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->teacherFacade = $teacherFacade;
        $this->subjectFacade = $subjectFacade;
        $this->locationFacade = $locationFacade;
        $this->timeTableFacade = $timeTableFacade;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function fill(array $data)
    {
        array_shift($data);

        foreach ($data as $rowArray) {
            $this->fillByRow($rowArray);
        }

    }

    public function fillByRow(array $rowArray)
    {
        $row = new DataRow($rowArray);

        $teacher = new Teacher();
        $teacher->setName($row->getTeacher());
        $teacher = $this->teacherFacade->insertIfNotExist($teacher);

        $subject = new Subject();
        $subject->setIndent($row->getIndent());
        $subject->setName($row->getName());
        $subject = $this->subjectFacade->insertIfNotExist($subject);

        $location = new Location();
        $location->setBuilding($this->resolveBuilding($row->getTimeLocation()));
        $location->setRoom((int)$this->resolveRoom($row->getTimeLocation()));
        $location = $this->locationFacade->insertIfNotExist($location);

        $timeTable = new TimeTableItem();
        $timeTable->setTeacher($teacher);
        $timeTable->setSubject($subject);
        $timeTable->setLocation($location);
        $timeTable->setCapacityFull($row->getCapacity());
        $timeTable->setCapacityClass1($row->getCapacityClass1());
        $timeTable->setCapacityClass2($row->getCapacityClass2());
        $timeTable->setCapacityClass3($row->getCapacityClass3());
        $timeTable->setActionType($this->resolveActionType($row->getAction()));
        $timeTable->setDay($this->resolveDay($row->getTimeLocation()));
        $timeTable->setTimeFrom($this->resolveTimeFrom($row->getTimeLocation()));
        $timeTable->setTimeTo($this->resolveTimeTo($row->getTimeLocation()));
        $this->timeTableFacade->insertIfNotExist($timeTable);
    }

    private function resolveActionType(string $type)
    {
        if ($type === 'Přednáška') {
            return TimeTableItem::ACTION_LECTURE;
        }
        if ($type === 'Cvičení') {
            return TimeTableItem::ACTION_SEMINAR;
        }

        return TimeTableItem::ACTION_OTHER;
    }

    private function resolveBuilding(string $str)
    {
        $data = explode(' ', $str);
        $building = $data[2];

        if(strpos($building, '?') !== false){
            return null;
        }
        return $building;
    }

    private function resolveRoom(string $str)
    {
        $data = explode(' ', $str);

        return $data[3];
    }

    private function resolveDay(string $str)
    {
        $data = explode(' ', $str);

        return $this->dateTimeFactory->getDayNum($data[0]);
    }

    private function resolveTimeFrom(string $str)
    {
        $data = explode(' ', $str);
        $times = explode('-', $data[1]);

        return $this->dateTimeFactory->fromFormat('H:i', $times[0])->getTime();
    }

    private function resolveTimeTo(string $str)
    {
        $data = explode(' ', $str);
        $times = explode('-', $data[1]);

        return $this->dateTimeFactory->fromFormat('H:i', $times[1])->getTime();
    }
}
