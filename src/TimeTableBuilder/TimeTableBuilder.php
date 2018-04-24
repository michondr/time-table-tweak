<?php

namespace App\TimeTableBuilder;

use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;

class TimeTableBuilder
{
    /**
     * @var TimeTableItemFacade
     */
    private $timeTableItemFacade;

    public function __construct(
        TimeTableItemFacade $timeTableItemFacade
    ) {
        $this->timeTableItemFacade = $timeTableItemFacade;
    }

    public function getTimeTables(array $formData)
    {
        $subjects = $formData['subjects'];
        $days = $formData['days'];

        $parents = [new TimeTable()];
        $children = [];

        $root = TreeNode::create(new TimeTable());

        foreach ($subjects as $subject) {

            $lectures = $this->timeTableItemFacade->getBySubjects([$subject], $days, true);
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($lectures as $lecture) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $lecture);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                    }
                }
            }

            $seminars = $this->timeTableItemFacade->getBySubjects([$subject], $days, false);
            /** @var TreeNode $leaf */
            foreach ($root->getLeaves() as $leaf) {
                foreach ($seminars as $seminar) {
                    $child = $this->addItemToTimeTable($leaf->getItem(), $seminar);
                    if (!is_null($child)) {
                        TreeNode::create($child, $leaf);
                    }
                }
            }
        }

        return $this->findDuplicities($root->getItemsOnHighestLeaves());
    }

    private function addItemToTimeTable(?TimeTable $timeTable, TimeTableItem $item)
    {
        $timeTableClone = $timeTable->copy();
        try {
            $timeTableClone->addItemToSchema($item);

            return $timeTableClone;
        } catch (SchemaLocationOccupiedException $e) {
//            dump($e->__toString());

            return null;
        }
    }

    private function findDuplicities(array $items)
    {
        $filtered = $items;

//        foreach ()
        return $filtered;
    }

}