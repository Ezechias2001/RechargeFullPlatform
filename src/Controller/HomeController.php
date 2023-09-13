<?php

namespace App\Controller;

use DateTime;
use App\Entity\Delai;
use App\Form\DelaiType;
use App\Form\margeType;
use App\Form\priseType;
use App\Entity\Notification;
use App\Entity\HistoriqueDePaye;
use App\Repository\UserRepository;
use App\Entity\DemandeDeValidation;
use App\Repository\DelaiRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\HistoriqueDePayeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandeDeValidationRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home'), 
    IsGranted('ROLE_USER')
    ]
    public function index(UserRepository $userRepository, DemandeDeValidationRepository $demandeDeValidationRepository): Response
    {   
        $user = $this->getUser();    
        $tableau = [];
        $recharges = [];
        $n = 0;
        $demandes = $demandeDeValidationRepository->findAll();
        
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
            'demandes' => $demandes,
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
    public function ShowSuperviseur(UserRepository $userRepository, $id, HistoriqueDePayeRepository $historiqueDePayeRepository): Response
    {   
        $user = $this->getUser();     
        
        // dd($recharges);
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez re

        $superviseur = $userRepository->findOneById($id);

        $recharges = $superviseur->getRecharges();

        $historiques = $historiqueDePayeRepository->findAllPayeBySupervisor($id);

        return $this->render('home/showSupervisor.html.twig', [
            'superviseur' => $superviseur,
            'recharges' => $recharges,
            'historiques' => $historiques,
            'role' => $roles[0],  
        ]);
    }
    
    #[Route('/compte', name: 'app_livret_de_compte'), 
    IsGranted('ROLE_USER')
    ]
    public function livretDeCompte(UserRepository $userRepository): Response
    {   
        $user = $this->getUser();    
        $tableau = [];
        $recharges = [];
        $n = 0;
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        if ($roles[0] === "ROLE_MARCHANT") {
            $recharges = $user->getRecharges();
            $dette = $user->getDette();
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

        return $this->render('home/livretDeCompte.html.twig', [
            'role' => $roles[0],
            'recharges' => $recharges,
            'user' =>  $user
        ]);
    }
    #[Route('/marge/{id}'  , name: 'app_marge'), 
    IsGranted('ROLE_SUPERVISEUR')
    ]
    public function definirMarge(Request $request, UserRepository $userRepository, $id, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();   
        $fils = $userRepository->findOneById($id);     
        $form  = $this->createForm(margeType::class, $fils);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marge = $form->get('marge')->getData();
            $fils->setMarge($marge);
           
            $this->addFlash('success', 'Votre formulaire a été soumis avec succès.');

            $entityManager->persist($fils);
            $entityManager->flush();

            return $this->redirectToRoute('app_supervisor_show', [ 'id' => $fils->getId() ], Response::HTTP_SEE_OTHER);
         } 

        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        return $this->render('home/marge.html.twig', [
            'role' => $roles[0],
            'user' =>  $user,
            'form' =>  $form,
            'fils' =>  $fils,
        ]);
    }

    #[Route('/notifierPrise/{id}', name: 'app_notifier_prise')]
    public function notifierPrise(DelaiRepository $delaiRepository ,Request $request, UserRepository $userRepository, $id, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();   
        $fils = $userRepository->findOneById($id);  
          
        $historiqueDePaye = new HistoriqueDePaye();
        $notification = new Notification();
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher
        
        $rolesFils = $fils->getRoles();
        
        $date = new DateTime('now');

        $delai = $delaiRepository->findOneByFils($fils);

        $form  = $this->createForm(priseType::class, $fils);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montant = $form->get('montantPris')->getData();

            if ($montant <= $fils->getDette()) {

                $fils->setDette( $fils->getDette()- $montant);
                $user->setDette($user->getDette()- $montant);
    
                $user->setMontantPris($user->getMontantPris() + $montant);
                $fils->setMontantPris($fils->getMontantPris() + $montant);
    
                $historiqueDePaye->setMontant($montant);
                $historiqueDePaye->setSuperviseur($user);
                $historiqueDePaye->setMarchand($fils);
                $historiqueDePaye->setStatut(0);
                $historiqueDePaye->setDate($date);

                $notification->setLibelle('Paiement');
                $notification->setFils($fils);
                $notification->setMontant($montant);
                
                $notification->setDate($date);
                

                $delai->setDate(null);
                
                if ($rolesFils[0] === "ROLE_SUPERVISEUR") {
                   $historiqueDePaye->setStatut(1);
                }
    
                $this->addFlash('success', 'Votre formulaire a été soumis avec succès.');
                // dd($historiqueDePaye, $notification);
                $entityManager->persist($historiqueDePaye);
                $entityManager->persist($notification);
                $entityManager->persist($fils);
                $entityManager->persist($user );
                $entityManager->persist($delai);
                $entityManager->flush();
            } else {
                # gestion d'erreur addflash...
            }
            
            return $this->redirectToRoute('app_supervisor_show', [ 'id' => $fils->getId() ], Response::HTTP_SEE_OTHER);
        } 

        return $this->render('home/notifierPrise.html.twig', [
            'role' => $roles[0],
            'user' =>  $user,
            'form' =>  $form,
            'fils' =>  $fils,
        ]);
    }

    #[Route('/notifications', name: 'app_notifications'), 
    IsGranted('ROLE_USER')
    ]
    public function Notification (NotificationRepository $notificationRepository): Response
    {   
        $user = $this->getUser();  
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher
        $notifications = $notificationRepository->findAll();
        //L'afficher en DESC stp Zec 

        return $this->render('home/notification.html.twig', [
            'role' => $roles[0],
            'notifications' => $notifications,
        ]);
    }

    #[Route('/historiqueDePaye', name: 'app_historique_de_paye'), 
    IsGranted('ROLE_USER')
    ]
    public function HistoriqueDePaye(HistoriqueDePayeRepository $historiqueDePayeRepository): Response
    {   
        $user = $this->getUser();  
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        $historiques  = $historiqueDePayeRepository->findAll(); 

        return $this->render('home/historiqueDePaye.html.twig', [
            'role' => $roles[0],
            'historiques' => $historiques,
        ]);
    }
    #[Route('/demanderValidation', name: 'app_demande_de_validation'), 
    IsGranted('ROLE_USER')
    ]
    public function DemandeValidation(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $demandeValidation = new DemandeDeValidation();

        $user = $this->getUser();  
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher
        $form = $this->createForm(priseType::class, $demandeValidation);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $montant = $form->get('montantPris')->getData();
            $demandeValidation->setMontant($montant);
            $demandeValidation->setDemandeur($user);
            $demandeValidation->setEtat(0);
            $entityManager->persist($demandeValidation);
            $entityManager->flush();            
            return $this->redirectToRoute('app_home');
        } 

        return $this->render('home/demandeDeValidation.html.twig', [
            'role' => $roles[0],
            'form' => $form->createView(),
        ]);
    }
    #[Route('/confirmerValidation/{id}', name: 'app_confirmer_validation'), 
    IsGranted('ROLE_USER')
    ]
    public function confirmationValidation( EntityManagerInterface $entityManager ,$id, DemandeDeValidationRepository $demandeDeValidationRepository) 
    {
        $demandeValidation = $demandeDeValidationRepository->findOneById($id);

        $montant = $demandeValidation->getMontant();
        $fils = $demandeValidation->getDemandeur();
        $user = $this->getUser();   
        $rolesFils = $fils->getRoles();
          
        $historiqueDePaye = new HistoriqueDePaye();
        $notification = new Notification();
        
        $date = new DateTime('now');
        
        $demandeValidation->setEtat(1);
        
        if ($montant <= $fils->getDette()) {

            $fils->setDette($fils->getDette()- $montant);
            $user->setDette($user->getDette()- $montant);

            $user->setMontantPris($user->getMontantPris() + $montant);
            $fils->setMontantPris($fils->getMontantPris() + $montant);

            $historiqueDePaye->setMontant($montant);
            $historiqueDePaye->setSuperviseur($user);
            $historiqueDePaye->setMarchand($fils);
            $historiqueDePaye->setStatut(0);
            $historiqueDePaye->setDate($date);

            $notification->setLibelle('Paiement');
            $notification->setFils($fils);
            $notification->setMontant($montant);
            $notification->setDate($date);
            
            if ($rolesFils[0] === "ROLE_SUPERVISEUR") {
               $historiqueDePaye->setStatut(1);
            };

            $this->addFlash('success', 'Votre formulaire a été soumis avec succès.');


            $entityManager->persist($historiqueDePaye);
            $entityManager->persist($notification);
            $entityManager->persist($fils);
            $entityManager->persist($user );
            $entityManager->persist($demandeValidation );
            $entityManager->flush();
        
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

        }
    }

    #[Route('/defenirUnDelai/{id}', name: 'app_delai_new', methods: ['GET', 'POST']),
    IsGranted('ROLE_SUPER_MARCHANT')
    ]
    public function defenirDelai($id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {   
        $user = $this->getUser();  
        
        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher

        $fils = $userRepository->findOneById($id);

        $delai = new Delai();
        $form = $this->createForm(DelaiType::class, $delai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $delai->setFils($fils);

            $entityManager->persist($delai);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/delai.html.twig', [
            'delai' => $delai,
            'form' => $form,
            'fils' => $fils,
            'role' => $roles[0],
        ]);
    }
    
}