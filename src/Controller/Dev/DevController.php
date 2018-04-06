<?php

namespace App\Controller\Dev;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DevController extends Controller
{
    /**
     * @Route("/dev", name="dev")
     */
    public function dev()
    {
        return $this->render(
            '@Controller/Dev/dev.html.twig',
            [
                'controller_name' => 'DevController',
                'data' => []
            ]
        );
    }

}
