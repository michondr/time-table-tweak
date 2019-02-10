<?php

namespace App\Controller\Registration;

use App\Controller\Flash;
use App\Entity\User\User;
use App\Entity\User\UserFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;
    private $userFacade;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserFacade $userFacade
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userFacade = $userFacade;
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            if($this->userFacade->isReadyToSave($user)){
                $this->addFlash(Flash::ERROR, 'Already exists user with this username or email');
                return $this->returnForm($form);
            }

            $this->userFacade->insertIfNotExist($user);

            $this->addFlash(Flash::SUCCESS, 'Registration successfull!');

            return $this->redirectToRoute('root');
        }

        return $this->returnForm($form);
    }

    private function returnForm(FormInterface $form)
    {
        return $this->render(
            '@Controller/Registration/registration.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}