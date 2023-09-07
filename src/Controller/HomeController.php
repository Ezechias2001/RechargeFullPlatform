<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home'), 
    IsGranted('ROLE_USER')
    ]
    public function index(UserRepository $userRepository): Response
    {   
        $user = $this->getUser();    
        $tableau = [];
        $recharges = [];
        $n = 0;
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        if ($roles[0] === "ROLE_MARCHANT") {
            $recharges = $user->getRecharges();
        } 
        elseif ($roles[0] === "ROLE_SUPERVISEUR") {
            $fils = $user->getUsers(); 
            foreach ($fils as $key => $i) {
                $tableau = $i->getRecharges();
                foreach ($tableau as $o) {
                    $recharges[$n] = $o ;
                    $n++;
                }
            }
        } elseif ($roles[0] === "ROLE_SUPER_MARCHANT") {
            $fils1 = $user->getUsers(); 
            foreach ($fils1 as $key => $i) {
                $fils = $i->getUsers(); 
                foreach ($fils as $key => $i) {
                    $tableau = $i->getRecharges();
                    foreach ($tableau as $o) {
                        $recharges[$n] = $o ;
                        $n++;
                    }
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'role' => $roles[0],
            'recharges' => $recharges,
        ]);
    }

    #[Route('/supervisors', name: 'app_supervisors'), 
    IsGranted('ROLE_USER')
    ]
    public function superviseur(UserRepository $userRepository): Response
    {   
        $user = $this->getUser();     
        
        // dd($recharges);
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        return $this->render('home/supervisors.html.twig', [
            'superviseurs' => $user->getUsers(),
            'role' => $roles[0],
        ]);
    }

    #[Route('/supervisor/{id}'  , name: 'app_supervisor_show'), 
    IsGranted('ROLE_USER')
    ]
    public function ShowSuperviseur(UserRepository $userRepository, $id): Response
    {   $user = $this->getUser();     
        
        // dd($recharges);
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez re

        $superviseur = $userRepository->findOneById($id);

        $recharges = $superviseur->getRecharges();

        return $this->render('home/showSupervisor.html.twig', [
            'superviseur' => $superviseur,
            'recharges' => $recharges,
            'role' => $roles[0],  
        ]);
    }
}
