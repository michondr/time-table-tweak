<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OverviewController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function index()
    {
        return $this->render('overview/index.html.twig', [
            'controller_name' => 'OverviewController',
        ]);
    }
}
