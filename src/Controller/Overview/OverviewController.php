<?php

namespace App\Controller\Overview;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function index()
    {
        dump($this->getUser());
        return $this->render(
            '@Controller/Overview/overview.html.twig',
            [
                'controller_name' => 'OverviewController',
            ]
        );
    }
}
