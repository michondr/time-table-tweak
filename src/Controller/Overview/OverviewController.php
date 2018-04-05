<?php

namespace App\Controller\Overview;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OverviewController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function index()
    {

        return $this->render('@Controller/note.html.twig', [
            'controller_name' => 'OverviewController',
        ]);
    }
}
