<?php

namespace App\Controller\Subject;

use App\Controller\Flash;
use App\Entity\Subject\SubjectFacade;
use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\Table\TimeTableFilter;
use App\TimeTableBuilder\TimeTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class SubjectController extends AbstractController
{
    private $subjectFacade;
    private $timeTableItemFacade;
    private $router;

    public function __construct(
        SubjectFacade $subjectFacade,
        TimeTableItemFacade $timeTableItemFacade,
        RouterInterface $router
    ) {
        $this->subjectFacade = $subjectFacade;
        $this->timeTableItemFacade = $timeTableItemFacade;
        $this->router = $router;
    }

    /**
     * @Route("/subjects", name="subject")
     */
    public function index(Request $request)
    {
        $this->addFlash(Flash::WARNING, 'All data is from SS 2018. I\'m slowly working on fix. You can get fresh data which insis provides in <a href="'.$this->router->generate('ez_insis.set').'">EZ INSIS</a> section');

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
            $indent = $subject->getName();
        }

        $filtered = TimeTableFilter::removeDays($timeTables);

        return $this->render(
            '@Controller/Subject/subjects.html.twig',
            [
                'indent' => $indent,
                'subjects' => $subjects,
                'time_tables' => $filtered['timetables'],
                'time_intervals' => $filtered['intervals'],
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
