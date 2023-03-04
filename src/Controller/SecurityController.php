<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $message = "on";

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
//        if($lastUsername){
//        }else{

//        }
        if($lastUsername){
            return $this->redirectToRoute('app_home',['last_username' => $lastUsername, 'error' => $error, 'message' => $message]);

        }else{
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'message' => $message]);

        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response

    {
        $session->remove('_security_main');
        return $this->redirectToRoute('app_home');
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
