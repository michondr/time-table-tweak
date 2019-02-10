<?php

namespace App\Controller\Overview;

use App\Controller\Flash;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class OverviewController extends AbstractController
{
    private $router;

    public function __construct(
        RouterInterface $router
    ) {
        $this->router = $router;
    }

    /**
     * @Route("/", name="root")
     */
    public function index()
    {
        $this->addFlash(Flash::WARNING, 'All data is from SS 2018. I\'m slowly working on fix. You can get fresh data which insis provides in <a href="'.$this->router->generate('ez_insis.set').'">EZ INSIS</a> section');

        return $this->render(
            '@Controller/Overview/overview.html.twig',
            [
                'controller_name' => 'OverviewController',
            ]
        );
    }
}
