<?php

namespace App\DateTime\Day;

class DayTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFunction('getDayName', [$this, 'getDayName']),
        ];
    }

    public function getDayName(int $day)
    {
        return Day::getDayName($day);
    }
}
