<?php

namespace App\Controller\Admin;

use App\Controller\Flash;
use App\DateTime\DateTimeFactory;
use App\Entity\User\UserFacade;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $userFacade;
    private $dateTimeFactory;

    public function __construct(
        UserFacade $userFacade,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->userFacade = $userFacade;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $users = $this->userFacade->getAll();

        return $this->render(
            '@Controller/Admin/admin.html.twig',
            [
                'users' => $users,
                'today' => $this->dateTimeFactory->now()->getDate(),
            ]
        );
    }

    /**
     * @Route("/admin/user/change_status/{id}", name="user.change_status")
     */
    public function changeUserStatus(int $id)
    {
        $result = $this->userFacade->changeStatus($id);
        if($result){
            $this->addFlash(Flash::SUCCESS, 'Successfully changed user status');
        } else{
            $this->addFlash(Flash::ERROR, 'This user is super admin, you cannot deactivate this fella');
        }

        return $this->redirectToRoute('admin');
    }
}
