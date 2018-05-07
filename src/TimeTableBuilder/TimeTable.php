<?php

namespace App\TimeTableBuilder;

use App\DateTime\Time\Time;
use App\DateTime\Time\TimeInterval;
use App\Entity\TimeTableItem\TimeTableItem;
use App\TimeTableBuilder\Cell\Cell;

class TimeTable
{
    const EMPTY = null;
    const WORKDAYS = [1, 2, 3, 4, 5, 6, 7];

    /** @var array[] $timeTableSchema */
    public $timeTableSchema = [];
    /** @var Cell $lastAddedItem */
    public $lastAddedItem = null;

    public function __construct()
    {
        foreach (self::WORKDAYS as $day) {
            foreach (self::getTimeIntervals() as $id => $interval) {
                $this->timeTableSchema[$day][$id] = self::EMPTY;
            }
        }
    }

    /**
     * @return TimeInterval[]
     */
    public static function getTimeIntervals()
    {
        return [
            '1' => new TimeInterval(new Time(7, 30), new Time(8, 15)),
            '2' => new TimeInterval(new Time(8, 15), new Time(9, 0)),
            '3' => new TimeInterval(new Time(9, 15), new Time(10, 0)),
            '4' => new TimeInterval(new Time(10, 0), new Time(10, 45)),
            '5' => new TimeInterval(new Time(11, 0), new Time(11, 45)),
            '6' => new TimeInterval(new Time(11, 45), new Time(12, 30)),
            '7' => new TimeInterval(new Time(12, 45), new Time(13, 30)),
            '8' => new TimeInterval(new Time(13, 30), new Time(14, 15)),
            '9' => new TimeInterval(new Time(14, 30), new Time(15, 15)),
            '10' => new TimeInterval(new Time(15, 15), new Time(16, 0)),
            '11' => new TimeInterval(new Time(16, 15), new Time(17, 0)),
            '12' => new TimeInterval(new Time(17, 0), new Time(17, 45)),
            '13' => new TimeInterval(new Time(18, 0), new Time(18, 45)),
            '14' => new TimeInterval(new Time(18, 45), new Time(19, 30)),
            '15' => new TimeInterval(new Time(19, 45), new Time(20, 30)),
            '16' => new TimeInterval(new Time(20, 30), new Time(21, 15)),
        ];
    }

    public static function getIntervalIdByStartTime(Time $time)
    {
        /**
         * @var int          $id
         * @var TimeInterval $interval
         */
        foreach (self::getTimeIntervals() as $id => $interval) {
            if ($interval->getFrom()->isSameAs($time)) {
                return $id;
            }
        }

        throw new \Exception('I dont have this start time: '.$time->toMySql());
    }

    public static function getIntervalIdByEndTime(Time $time)
    {
        /**
         * @var int          $id
         * @var TimeInterval $interval
         */
        foreach (self::getTimeIntervals() as $id => $interval) {
            if ($interval->getTo()->isSameAs($time)) {
                return $id;
            }
        }

        throw new \Exception('I dont have this end time: '.$time->toMySql());
    }

    public function addCellToSchema(Cell $cell)
    {
        foreach ($cell->getOccupiedIds() as $id) {

            /** @var TimeTableItem $elementOnLocation */
            $elementOnLocation = $this->timeTableSchema[$cell->getDay()][$id];
            if ($elementOnLocation !== self::EMPTY) {
                throw new SchemaLocationOccupiedException($cell);
            }

            $this->timeTableSchema[$cell->getDay()][$id] = $cell;
            $this->lastAddedItem = $cell;
        }
    }

    public function getSubjects(bool $returnIndents = false)
    {
        $subjects = [];

        foreach ($this->timeTableSchema as $day) {
            /** @var Cell $item */
            foreach (array_filter($day) as $item) {
                $subjects[] = $returnIndents ? $item->getSubject()->getIndent() : $item->getSubject();
            }
        }

        return array_unique($subjects);
    }

    public function getLastAddedItem(): ?Cell
    {
        return $this->lastAddedItem;
    }

    public function __toString()
    {
        return json_encode($this->timeTableSchema);
    }

    /**
     * all indexes are in interval <0;1>
     * $dayDispersionIndex - "blockness" of day - the more spaces are between subjects, the lower index
     * $freeDayIndex - the more free days I have, the higher number
     *
     * max value is 1 - that means, that all subjects ar in one block.
     * the lower this value is, the more are subjects in timetable spread out
     *
     * @return float
     */
    public function calculateIndex()
    {
        $semiIndex = 0;
        $freeDays = 0;

        foreach ($this->timeTableSchema as $day) {
            $ids = array_keys(array_filter($day));
            if (empty($ids)) {
                $freeDays++;
                continue;
            }

            $size = count($ids);
            $first = current($ids);
            $last = end($ids);

            $fullSize = $last - $first + 1;
            $semiIndex += $size / $fullSize;
        }

        $dayDispersionIndex = $semiIndex / count(self::WORKDAYS);

        return round($dayDispersionIndex + $freeDays, 3);
    }

    public function isDayEmpty(int $day)
    {
        return empty(array_filter($this->timeTableSchema[$day]));
    }
}
