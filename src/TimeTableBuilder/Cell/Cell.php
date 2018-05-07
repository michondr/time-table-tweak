<?php

namespace App\TimeTableBuilder\Cell;

use App\Entity\Subject\Subject;
use App\Entity\TimeTableItem\TimeTableItem;
use App\TimeTableBuilder\Table\TimeTableInterval;

class Cell implements \JsonSerializable
{
    private $subject;
    private $actionType;
    private $day;
    private $idFrom;
    private $idTo;

    public function __construct(
        TimeTableItem $timeTableItem
    ) {
        $this->subject = $timeTableItem->getSubject();
        $this->actionType = $timeTableItem->getActionType();
        $this->day = $timeTableItem->getDay();
        $this->idFrom = TimeTableInterval::getIntervalIdByStartTime($timeTableItem->getTimeFrom());
        $this->idTo = TimeTableInterval::getIntervalIdByEndTime($timeTableItem->getTimeTo());
    }

    public function getHash()
    {
        return md5($this->subject->getIndent().$this->day.$this->actionType.$this->idFrom.$this->idTo);;
    }

    public function jsonSerialize()
    {
        return json_encode(
            [
                'subject' => $this->subject->getIndent(),
                'actionType' => $this->actionType,
                'from' => $this->idFrom,
                'to' => $this->idTo,
            ]
        );
    }

    public function getOccupiedIds()
    {
        $ids = [];

        for ($i = $this->idFrom; $i <= $this->idTo; $i++) {
            $ids[] = $i;
        }

        return $ids;
    }

    /**
     * @return Subject|null
     */
    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function getIdFrom(): int
    {
        return $this->idFrom;
    }

    /**
     * @return int
     */
    public function getIdTo(): int
    {
        return $this->idTo;
    }
}
