<?php

namespace App\Controller\TimeTable;

use App\Controller\Flash;
use App\Entity\Subject\SubjectFacade;
use App\TimeTableBuilder\Table\TimeTableFilter;
use App\TimeTableBuilder\TimeTableBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TimeTableController extends AbstractController
{
    const MAX_TABLE_SELECT_LIMIT = 25;

    private $subjectFacade;
    private $timeTableBuilder;
    private $router;

    public function __construct(
        SubjectFacade $subjectFacade,
        TimeTableBuilder $timeTableBuilder,
        RouterInterface $router
    ) {
        $this->subjectFacade = $subjectFacade;
        $this->timeTableBuilder = $timeTableBuilder;
        $this->router = $router;
    }

    /**
     * @Route("/time_table", name="time_table")
     */
    public function index(Request $request)
    {
        $this->addFlash(Flash::WARNING, 'All data is from SS 2018. I\'m slowly working on fix. You can get fresh data which insis provides in <a href="'.$this->router->generate('ez_insis.set').'">EZ INSIS</a> section');

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
            $filtered = TimeTableFilter::removeDays($timetables);

            return $this->render(
                '@Controller/TimeTable/timeTableResult.twig',
                [
                    'time_tables' => $filtered['timetables'],
                    'form_subjects' => $setupForm->getData()['subjects'],
                    'time_intervals' => $filtered['intervals'],
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
