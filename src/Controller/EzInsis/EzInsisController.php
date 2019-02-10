<?php

namespace App\Controller\EzInsis;

use App\Controller\EzInsis\Form\ItemFormEntity;
use App\Controller\EzInsis\Form\ItemFormType;
use App\Controller\EzInsis\Form\SetFormEntity;
use App\Controller\EzInsis\Form\SetFormType;
use App\Controller\Flash;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EzInsisController extends AbstractController
{
    const EZ_INSIS_API_KEY = '1ec797627eca0f34fd2377c983ce37b9';

    private $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/ez-insis/set", name="ez_insis.set")
     */
    public function setAction(Request $request)
    {
        $data = [];
        $entity = new SetFormEntity();
        $form = $this->formFactory->create(
            SetFormType::class,
            $entity
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = 'http://ez-insis.michondr.cz/data/time-table/set'.$entity->toUrl(self::EZ_INSIS_API_KEY);

            $result = file_get_contents($url);

            if ($result !== false) {
                $this->addFlash(Flash::SUCCESS, 'successfully loaded data');
                $data = json_decode($result, true);
            } else {
                $this->addFlash(Flash::ERROR, 'Failed to load data');
            }
        }

        return $this->render(
            '@Controller/EzInsis/ezInsisSet.html.twig',
            [
                'form' => $form->createView(),
                'data' => $data,
            ]
        );
    }

    /**
     * @Route("/ez-insis/item/{timeTableItemId}", name="ez_insis.item")
     */
    public function itemAction(Request $request, int $timeTableItemId)
    {
        $data = [];
        $entity = new ItemFormEntity();
        $form = $this->formFactory->create(
            ItemFormType::class,
            $entity
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = 'http://ez-insis.michondr.cz/data/time-table/item'.$entity->toUrl($timeTableItemId, self::EZ_INSIS_API_KEY);

            $result = file_get_contents($url);

            if ($result !== false) {
                $this->addFlash(Flash::SUCCESS, 'successfully loaded data');
                $data = json_decode($result, true);
            } else {
                $this->addFlash(Flash::ERROR, 'Failed to load data');
            }
        }

        return $this->render(
            '@Controller/EzInsis/ezInsisItem.html.twig',
            [
                'form' => $form->createView(),
                'data' => $data,
            ]
        );
    }
}
