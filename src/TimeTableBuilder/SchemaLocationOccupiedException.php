<?php

namespace App\TimeTableBuilder;

use App\TimeTableBuilder\Cell\Cell;
use App\TimeTableBuilder\Table\TimeTableInterval;

class SchemaLocationOccupiedException extends \Exception
{
    private $item;

    public function __construct(Cell $item, $message = 'this spot is full', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->item = $item;
    }

    public function __toString()
    {
        return
            $this->item->getSubject()->getIndent().
            ' ('.$this->item->getActionType().')'.
            ' on '.$this->item->getDay().
            ' from '.TimeTableInterval::getIntervals()[$this->item->getIdFrom()]->getFrom()->toMySql().
            ' to '.TimeTableInterval::getIntervals()[$this->item->getIdTo()]->getTo()->toMySql().
            ' is full';
    }
}
