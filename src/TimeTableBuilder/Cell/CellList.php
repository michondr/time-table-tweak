<?php

namespace App\TimeTableBuilder\Cell;

use App\Entity\Subject\Subject;
use App\Entity\TimeTableItem\TimeTableItem;

class CellList
{
    private $cells;

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public static function constructFromTimeTableItems(array $timeTableItems)
    {
        $newCells = [];
        /** @var TimeTableItem $timeTableItem */
        foreach ($timeTableItems as $timeTableItem) {
            $newCell = new Cell($timeTableItem);
            $newCells[$newCell->getHash()] = $newCell;
        }

        return new CellList($newCells);
    }

    public function getSortedCellList(Subject $subject, $actionType)
    {
        $validCells = [];

        /** @var Cell $cell */
        foreach ($this->cells as $cell) {
            if ($cell->getSubject()->getIndent() === $subject->getIndent() and $cell->getActionType() === $actionType) {
                $validCells[] = $cell;
            }
        }

        return new CellList($validCells);
    }

    public function getCells()
    {
        return $this->cells;
    }

    public function isEmpty()
    {
        return empty($this->cells);
    }
}


