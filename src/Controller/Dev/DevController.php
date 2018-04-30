<?php

namespace App\Controller\Dev;

use App\Entity\TimeTableItem\TimeTableItemFacade;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DevController extends Controller
{
    private $tableItemFacade;

    public function __construct(
        TimeTableItemFacade $tableItemFacade
    ) {
        $this->tableItemFacade = $tableItemFacade;
    }

    /**
     * @Route("/dev", name="dev")
     */
    public function dev()
    {

        throw new \Exception('broken here', 205);
        return $this->render(
            '@Controller/Dev/dev.html.twig',
            [
                'controller_name' => 'DevController',
                'data' => ['hello' => 'ewew'],
            ]
        );
    }

}
