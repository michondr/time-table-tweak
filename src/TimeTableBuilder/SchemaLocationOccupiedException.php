<?php

namespace App\TimeTableBuilder;

use App\Entity\TimeTableItem\TimeTableItem;

class SchemaLocationOccupiedException extends \Exception
{
    private $item;

    public function __construct(TimeTableItem $item, $message = 'this spot is full', $code = 0, \Exception $previous = null)
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
            ' from '.$this->item->getTimeFrom()->toMySql().
            ' to '.$this->item->getTimeTo()->toMySql().
            ' is full';
    }
}
