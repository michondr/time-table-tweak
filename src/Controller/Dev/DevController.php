<?php

namespace App\Controller\Dev;

use App\Entity\TimeTableItem\TimeTableItem;
use App\Entity\TimeTableItem\TimeTableItemFacade;
use App\TimeTableBuilder\TimeTable;
use App\TimeTableBuilder\TreeNode;
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
        $root = TreeNode::create(new TimeTable());

        $ch1 = TreeNode::create(new TimeTable(), $root);
        $ch2 = TreeNode::create(new TimeTable(), $root);
        $ch3 = TreeNode::create(new TimeTable(), $root);

//        $ch11 = TreeNode::create(new TimeTable(), $ch1);
//        $ch111 = TreeNode::create(new TimeTable(), $ch11);
//        $ch112 = TreeNode::create(new TimeTable(), $ch11);
        dump($root);
        dump($root->getLeaves());
//        dump($ch11->getRoot());
//        dump($ch11->getRoot()->getLeaves());

        die;
        $item = new TimeTableItem();
        $item->setDay(2);
        dump($item);
        dump($item->hasEmptyFields());

        die;
        $t = new TimeTable([1, 4]);
        $t->addItemToSchema($this->tableItemFacade->getById(3));

        $t2 = $t->copy();

        dump($t);
        dump($t2);

        return $this->render(
            '@Controller/Dev/dev.html.twig',
            [
                'controller_name' => 'DevController',
                'data' => [],
            ]
        );
    }

}
