<?php

namespace App\TimeTableBuilder;

class TimeTableFilter
{
    public static function removeDays(array $timetables)
    {
        $intervals = TimeTable::getTimeIntervals();
        $emptyLateHours = true;

        /** @var TimeTable $timetable */
        foreach ($timetables as $timetable) {
            $saturday = $timetable->timeTableSchema[6];
            $sunday = $timetable->timeTableSchema[7];

            if (empty(array_filter(array_merge($saturday, $sunday)))) {
                unset($timetable->timeTableSchema[6]);
                unset($timetable->timeTableSchema[7]);
            }

            foreach ($timetable->timeTableSchema as $daySchema) {
                if ($daySchema[15] !== TimeTable::EMPTY or $daySchema[16] !== TimeTable::EMPTY) {
                    $emptyLateHours = false;
                }
            }
        }

        if ($emptyLateHours) {
            foreach ($timetables as $timetable) {
                foreach ($timetable->timeTableSchema as $key => $daySchema) {
                    unset($timetable->timeTableSchema[$key][15]);
                    unset($timetable->timeTableSchema[$key][16]);
                }
            }

            unset($intervals[15]);
            unset($intervals[16]);
        }

        return ['timetables' => $timetables, 'intervals' => $intervals];
    }
}
