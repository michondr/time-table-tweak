<?php

namespace App\Controller\Subject;

use App\DateTime\Day\Day;
use App\Entity\Subject\SubjectFacade;
use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\SchemaLocationOccupiedException;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends Controller
{
    private $subjectFacade;
    private $timeTableItemFacade;
    private $timeTableBuilder;

    public function __construct(
        SubjectFacade $subjectFacade,
        TimeTableItemFacade $timeTableItemFacade,
        TimeTableBuilder $timeTableBuilder
    ) {
        $this->subjectFacade = $subjectFacade;
        $this->timeTableItemFacade = $timeTableItemFacade;
        $this->timeTableBuilder = $timeTableBuilder;
    }

    /**
     * @Route("/subjects", name="subject.overview")
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
            '@Controller/Subject/subjectsOverview.html.twig',
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
            try {
                $t->addItemToSchema($item);
            } catch (SchemaLocationOccupiedException $e) {
                var_dump(
                    'could not add '.$item->getActionType().
                    ' on '.Day::getDayName($item->getDay()).
                    ' from '.$item->getTimeFrom()->toMySql().
                    ' to '.$item->getTimeTo()->toMySql()
                );
            }
        }

        return $t;
    }
}
