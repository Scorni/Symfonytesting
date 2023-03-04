<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request,AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        if ($lastUsername) {
            $message = "Utilisateur connecté : ". $lastUsername;
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'message' => $message,
            ]);
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'message' => false
        ]);
    }
}
