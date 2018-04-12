<?php

namespace App\DateTime\Day;

class Day
{
    const DAY_MONDAY = 1;
    const DAY_TUESDAY = 2;
    const DAY_WEDNESDAY = 3;
    const DAY_THURSDAY = 4;
    const DAY_FRIDAY = 5;
    const DAY_SATURDAY = 6;
    const DAY_SUNDAY = 7;

    public static function getDayNames()
    {
        return [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];
    }

    public static function getDayNamesCzech()
    {
        return [
            1 => 'Pondělí',
            2 => 'Úterý',
            3 => 'Středa',
            4 => 'Čtvrtek',
            5 => 'Pátek',
            6 => 'Sobota',
            7 => 'Neděle',
        ];
    }

    public static function getDayName(int $day, bool $cz = false)
    {
        if ($cz) {
            $name = self::getDayNamesCzech()[$day];
        } else {
            $name = self::getDayNames()[$day];
        }

        if (is_null($name)) {
            throw new \Exception('Unsupported day');
        }

        return $name;
    }
}
