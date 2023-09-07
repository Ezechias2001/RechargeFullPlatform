<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_profile', methods: ["GET","POST"] ),
    IsGranted('ROLE_USER')
    ]
    public function index(): Response
    {   $user = $this->getUser(); 

        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher
        return $this->render('user/profile/index.html.twig', [
            'role' => $roles[0],
        ]);
    }
}
