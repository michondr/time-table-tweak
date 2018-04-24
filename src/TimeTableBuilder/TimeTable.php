<?php

namespace App\TimeTableBuilder;

use App\DateTime\Time\Time;
use App\DateTime\Time\TimeInterval;
use App\Entity\TimeTableItem\TimeTableItem;

class TimeTable
{
    const EMPTY = null;

    /** @var array[] TimeTableItem */
    public $timeTableSchema = [];

    public function __construct()
    {
        $days = [1, 2, 3, 4, 5];

        foreach ($days as $day) {
            foreach (self::getTimeIntervals() as $id => $interval) {
                $this->timeTableSchema[$day][$id] = self::EMPTY;
            }
        }
    }

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

    public function addItemToSchema(TimeTableItem $item)
    {
        foreach ($item->getTimeTableOccupiedIds() as $id) {

            /** @var TimeTableItem $elementOnLocation */
            $elementOnLocation = $this->timeTableSchema[$item->getDay()][$id];
            if ($elementOnLocation !== self::EMPTY) {
                throw new SchemaLocationOccupiedException($item);
            }

            $this->timeTableSchema[$item->getDay()][$id] = $item;
        }
    }

    public function copy()
    {
        return clone $this;
    }

    public function getSize()
    {
        $count = 0;
        foreach ($this->timeTableSchema as $day) {
            $count += count(array_filter($day));
        }

        return $count;
    }
}
