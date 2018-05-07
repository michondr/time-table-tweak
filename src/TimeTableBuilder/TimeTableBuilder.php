<?php

namespace App\TimeTableBuilder;

use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\Cell\Cell;
use App\TimeTableBuilder\Cell\CellList;

class TimeTableBuilder
{
    const MAX_RETURN_SIZE = 10;
    const MAX_SUB_SIZE = 10;

    private $timeTableItemFacade;

    public function __construct(
        TimeTableItemFacade $timeTableItemFacade
    ) {
        $this->timeTableItemFacade = $timeTableItemFacade;
    }

    public function getTimeTablesMulti(array $subjects)
    {
        $items = [];
        foreach (range(1, count($subjects)) as $index) {
            array_push($subjects, array_shift($subjects));
            $items = array_merge($items, $this->getTimeTables($subjects));
        }

        $items = array_unique($items);
        $items = $this->sort($items);
        $items = array_slice($items, 0, self::MAX_RETURN_SIZE);

        return $this->hydrateCells($items);
    }

    public function getTimeTables(array $subjects)
    {
        $timeTableItems = $this->timeTableItemFacade->getBySubjects($subjects);
        $cellList = CellList::constructFromTimeTableItems($timeTableItems);

        $root = TreeNode::create(new TimeTable());

        foreach ($subjects as $subject) {
            $lectureList = $cellList->getSortedCellList($subject, TimeTableItem::ACTION_LECTURE);

            $lecturesAdded = 0;
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($lectureList->getCells() as $cell) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $cell);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                        $lecturesAdded++;
                    }
                }
            }
            $lecturesAddedCorrectly = ($lectureList->isEmpty() or $lecturesAdded > 0);
            if (!$lecturesAddedCorrectly) {
                continue;
            }

            $seminarList = $cellList->getSortedCellList($subject, TimeTableItem::ACTION_SEMINAR);
            $seminarsAdded = 0;
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($seminarList->getCells() as $seminar) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $seminar);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                        $seminarsAdded++;
                    }
                }
            }

            $seminarsAddedCorrectly = ($seminarList->isEmpty() or $seminarsAdded > 0);
            if (!$seminarsAddedCorrectly) {
                foreach ($root->getLeaves() as $leaf) {

                    /** @var TimeTable $item */
                    $item = $leaf->getItem();
                    $lastItem = $item->getLastAddedItem();

                    if (!is_null($lastItem) and $lastItem->getSubject() == $subject and $lastItem->getActionType() == TimeTableItem::ACTION_LECTURE) {
                        $root->removeLeaf($leaf);
                    }
                }
            }
        }

        $items = $root->getItemsOnHighestLeaves();
        $items = $this->sort($items);

        return array_slice($items, 0, self::MAX_SUB_SIZE);
    }

    private function addItemToTimeTable(?TimeTable $timeTable, Cell $cell)
    {
        $timeTableClone = clone $timeTable;
        try {
            $timeTableClone->addCellToSchema($cell);

            return $timeTableClone;
        } catch (SchemaLocationOccupiedException $e) {
//            dump($e->__toString());

            return null;
        }
    }

    private function sort(array $items)
    {
        $result = usort(
            $items,
            function ($a, $b) {
                /** @var TimeTable $a */
                /** @var TimeTable $b */
                if (count($a->getSubjects()) == count($b->getSubjects())) {
                    return $a->calculateIndex() < $b->calculateIndex();
                } else {
                    return count($a->getSubjects()) < count($b->getSubjects());
                }
            }
        );

        if ($result === false) {
            throw new \Exception('TimeTableBuilder was unable to sort by index');
        }

        return $items;
    }

    private function hydrateCells(array $timeTables)
    {
        /** @var TimeTable $timeTable */
        foreach ($timeTables as $tableKey => $timeTable) {
            foreach ($timeTable->timeTableSchema as $dayKey => $daySchema) {
                foreach (array_filter($daySchema) as $cellKey => $cell) {
                    $timeTables[$tableKey]->timeTableSchema[$dayKey][$cellKey] = $this->timeTableItemFacade->getByCellData($cell);
                }
            }
        }

        return $timeTables;
    }
}
