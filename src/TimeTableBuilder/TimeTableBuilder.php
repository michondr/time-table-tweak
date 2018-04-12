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

    public function getTimeTable(array $formData)
    {
        $subjects = $formData['subjects'];
        $days = $formData['days'];

        $timeTable = new TimeTable($days);

        foreach ($subjects as $subject){
            $lectures = $this->timeTableItemFacade->getBySubjects([$subject], $days, true);
            $this->addItemsToTimeTable($timeTable, $lectures);

            $seminars = $this->timeTableItemFacade->getBySubjects([$subject], $days, false);
            $this->addItemsToTimeTable($timeTable, $seminars);
        }


//        die;
        return $timeTable;
    }

    private function addItemsToTimeTable(TimeTable $timeTable, array $items)
    {
        /** @var TimeTableItem $item */
        foreach ($items as $item) {
            $ids = $item->getTimeTableOccupiedIds();
            if (count($ids) > 4) {
                continue;
            }

            try {
                $timeTable->addItemToSchema($item);
            } catch (SchemaLocationOccupiedException $e) {
                dump($e->__toString());
            }
        }
    }
}
