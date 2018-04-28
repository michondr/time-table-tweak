<?php

namespace App\TimeTableBuilder;

use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;

class TimeTableBuilder
{
    private $timeTableItemFacade;

    public function __construct(
        TimeTableItemFacade $timeTableItemFacade
    ) {
        $this->timeTableItemFacade = $timeTableItemFacade;
    }

    public function getTimeTablesMulti(array $formData)
    {
        $subjects = $formData['subjects'];
        $items = [];
        foreach (range(1, count($subjects)) as $index) {
            array_push($subjects, array_shift($subjects));
            $items = array_merge($items, $this->getTimeTables($subjects));
        }

        $items = array_unique($items);
        $items = $this->sort($items);

        return $items;
    }

    public function getTimeTables(array $subjects)
    {
        $root = TreeNode::create(new TimeTable());

        foreach ($subjects as $subject) {

            $lectures = $this->timeTableItemFacade->getBySubjects([$subject], true);
            $lecturesAdded = 0;
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($lectures as $lecture) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $lecture);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                        $lecturesAdded++;
                    }
                }
            }
            $lecturesAddedCorrectly = (empty($lectures) or $lecturesAdded > 0);
            if (!$lecturesAddedCorrectly) {
                continue;
            }

            $seminars = $this->timeTableItemFacade->getBySubjects([$subject], false);
            $seminarsAdded = 0;
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($seminars as $seminar) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $seminar);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                        $seminarsAdded++;
                    }
                }
            }

            $seminarsAddedCorrectly = (empty($seminars) or $seminarsAdded > 0);
            if (!$seminarsAddedCorrectly) {
                foreach ($root->getLeaves() as $leaf) {

                    /** @var TimeTable $item */
                    $item = $leaf->getItem();
                    $lastItem = $item->getLastAddedItem();

                    if (!is_null($lastItem) and $lastItem->getSubject() == $subject and $lastItem->getActionType() == 'lecture') {
                        $root->removeLeaf($leaf);
                    }
                }
            }
        }

        $items = $root->getItemsOnHighestLeaves();
        $items = $this->sort($items);

        return array_slice($items, 0, 15);
    }

    private function addItemToTimeTable(?TimeTable $timeTable, TimeTableItem $item)
    {
        $timeTableClone = clone $timeTable;
        try {
            $timeTableClone->addItemToSchema($item);

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
}
