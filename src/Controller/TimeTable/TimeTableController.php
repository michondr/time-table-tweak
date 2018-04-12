<?php

namespace App\Controller\TimeTable;

use App\Controller\Flash;
use App\DateTime\Day\Day;
use App\Entity\Subject\SubjectFacade;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends Controller
{
    /**
     * @var SubjectFacade
     */
    private $subjectFacade;
    /**
     * @var TimeTableBuilder
     */
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

            $timetable = $this->timeTableBuilder->getTimeTable($setupForm->getData());
            $this->addFlash(Flash::INFO, 'Successfully imported');

            return $this->render(
                '@Controller/TimeTable/timeTableResult.twig',
                [
                    'controller_name' => 'TimeTableBuilder - Result',
                    'time_table' => $timetable,
                    'form_data' => $setupForm->getData(),
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

    public function getSubjectsForm()
    {
        $form = $this->createFormBuilder()
            ->add(
                'subjects',
                ChoiceType::class,
                [
                    'choices' => $this->subjectFacade->getAll(),
                    'choice_label' => 'viewName',
                    'choice_value' => 'id',
                    'expanded' => true,
                    'multiple' => true,
                    'label_attr' => ['class' => 'checkbox-custom'],
                ]
            )
            ->add(
                'days',
                ChoiceType::class,
                [
                    'choices' => [
                        'Monday' => '1',
                        'Tuesday' => '2',
                        'Wednesday' => '3',
                        'Thursday' => '4',
                        'Friday' => '5',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'label_attr' => ['class' => 'checkbox-custom'],
                    'choice_attr' => function () {
                        return ['checked' => 'checked'];
                    },
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => 'btn-primary']]
            )
            ->getForm();

        return $form;
    }
}
