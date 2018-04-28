<?php

namespace App\Controller\TimeTable;

use App\Entity\Subject\SubjectFacade;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends Controller
{
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

            $timetables = $this->timeTableBuilder->getTimeTablesMulti($setupForm->getData());

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
