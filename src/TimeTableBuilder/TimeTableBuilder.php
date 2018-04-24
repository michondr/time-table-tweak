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

    public function getTimeTables(array $formData)
    {
        $subjects = $formData['subjects'];
        $days = $formData['days'];

        $root = TreeNode::create(new TimeTable());

        foreach ($subjects as $subject) {
            $baseRoot = clone $root;

            $lectures = $this->timeTableItemFacade->getBySubjects([$subject], $days, true);
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
                $root = $baseRoot;
                continue;
            }

            $seminars = $this->timeTableItemFacade->getBySubjects([$subject], $days, false);
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
            if (!$seminarsAddedCorrectly and !$lecturesAddedCorrectly) {
                $root = $baseRoot;
            }
        }

        $leaves = $root->getItemsOnHighestLeaves();

        $result = usort(
            $leaves,
            function ($a, $b) {
                /** @var TimeTable $a */
                /** @var TimeTable $b */
                return $a->calculateIndex() < $b->calculateIndex();
            }
        );

        if ($result === false) {
            throw new \Exception('TimeTableBuilder was unable to sort leaves');
        }

        return $leaves;
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
}
