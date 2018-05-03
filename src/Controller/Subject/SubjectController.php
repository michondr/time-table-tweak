<?php

namespace App\Controller\Subject;

use App\Entity\Subject\SubjectFacade;
use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\TimeTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends Controller
{
    private $subjectFacade;
    private $timeTableItemFacade;

    public function __construct(
        SubjectFacade $subjectFacade,
        TimeTableItemFacade $timeTableItemFacade
    ) {
        $this->subjectFacade = $subjectFacade;
        $this->timeTableItemFacade = $timeTableItemFacade;
    }

    /**
     * @Route("/subjects", name="subject")
     */
    public function index(Request $request)
    {
        $indent = $request->query->get('indent');
        $subjects = [];
        $timeTables = [];

        if (is_null($indent)) {
            $subjects = $this->subjectFacade->getAll();
        } else {
            $subject = $this->subjectFacade->getByIndent($indent);
            $lectures = $this->timeTableItemFacade->getBySubjects([$subject], true);
            $seminars = $this->timeTableItemFacade->getBySubjects([$subject], false);
            $timeTables = [$this->createTimeTable($lectures), $this->createTimeTable($seminars)];
        }

        return $this->render(
            '@Controller/Subject/subjects.html.twig',
            [
                'subjects' => $subjects,
                'time_tables' => $timeTables,
                'time_intervals' => TimeTable::getTimeIntervals(),
            ]
        );
    }

    private function createTimeTable(array $items)
    {
        $t = new TimeTable();
        /** @var TimeTableItem $item */
        foreach ($items as $item) {
            foreach ($item->getTimeTableOccupiedIds() as $id) {
                $t->timeTableSchema[$item->getDay()][$id][] = $item;
            }
        }

        return $t;
    }
}
