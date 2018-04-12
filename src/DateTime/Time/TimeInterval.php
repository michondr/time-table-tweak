<?php

namespace App\DateTime\Time;

class TimeInterval
{
    private $from;
    private $to;

    public function __construct(Time $from, Time $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }
}