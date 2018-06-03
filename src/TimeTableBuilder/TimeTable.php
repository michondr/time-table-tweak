<?php

namespace App\TimeTableBuilder;

use App\Entity\TimeTableItem\TimeTableItem;
use App\TimeTableBuilder\Cell\Cell;
use App\TimeTableBuilder\Table\TimeTableInterval;

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
            foreach (TimeTableInterval::getIntervals() as $id => $interval) {
                $this->timeTableSchema[$day][$id] = self::EMPTY;
            }
        }
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

    public function getSubjects($returnIndents = false)
    {
        $subjects = [];

        foreach ($this->timeTableSchema as $daySchema) {
            /** @var Cell $item */
            foreach (array_filter($daySchema) as $item) {
                if (is_array($item)) {
                    /** @var TimeTableItem $tableItem */
                    foreach ($item as $tableItem) {
                        $subjects[] = $returnIndents ? $tableItem->getSubject()->getIndent() : $tableItem->getSubject();
                    }

                } else {
                    $subjects[] = $returnIndents ? $item->getSubject()->getIndent() : $item->getSubject();
                }
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
