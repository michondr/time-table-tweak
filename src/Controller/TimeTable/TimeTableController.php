<?php

namespace App\Controller\TimeTable;

use App\Controller\Flash;
use App\Entity\Subject\SubjectFacade;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends Controller
{
    const MAX_TABLE_SELECT_LIMIT = 25;

    private $subjectFacade;
    private $timeTableBuilder;

    public function __construct(
        SubjectFacade $subjectFacade,
        TimeTableBuilder $timeTableBuilder
    ) {
        $this->subjectFacade = $subjectFacade;
        $this->timeTableBuilder = $timeTableBuilder;
    }

    /**
     * @Route("/time_table", name="time_table")
     */
    public function index(Request $request)
    {
        $setupForm = $this->getSubjectsForm();
        $setupForm->handleRequest($request);

        if ($setupForm->isSubmitted() and $setupForm->isValid()) {

            if (count($setupForm->getData()['subjects']) > self::MAX_TABLE_SELECT_LIMIT) {
                $this->addFlash(Flash::WARNING, 'Please select no more than '.self::MAX_TABLE_SELECT_LIMIT.' subjects at once');

                return $this->render(
                    '@Controller/TimeTable/timeTableSetup.twig',
                    [
                        'subjects_form' => $setupForm->createView(),
                    ]
                );
            }

            $timetables = $this->timeTableBuilder->getTimeTablesMulti($setupForm->getData()['subjects']);

            return $this->render(
                '@Controller/TimeTable/timeTableResult.twig',
                [
                    'time_tables' => $timetables,
                    'form_subjects' => $setupForm->getData()['subjects'],
                    'time_intervals' => TimeTable::getTimeIntervals(),
                ]
            );
        }

        return $this->render(
            '@Controller/TimeTable/timeTableSetup.twig',
            [
                'controller_name' => 'TimeTableBuilder - Setup',
                'subjects_form' => $setupForm->createView(),
            ]
        );
    }

    private function getSubjectsForm()
    {
        $form = $this->createFormBuilder()
            ->add(
                'subjects',
                ChoiceType::class,
                [
                    'choices' => $this->subjectFacade->getAll(),
                    'choice_label' => 'viewName',
                    'choice_value' => 'indent',
                    'expanded' => true,
                    'multiple' => true,
                    'label_attr' => ['class' => 'checkbox-custom'],
                ]
            )
            ->getForm();

        return $form;
    }
}
