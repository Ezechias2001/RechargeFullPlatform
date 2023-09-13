<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\makeMarchandType;
use App\Form\marchantFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register'),
    IsGranted("ROLE_SUPER_MARCHANT")]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {   
        $pere = $this->getUser();

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $role = $form->get('role')->getData();

            $user->setRoles($role);
            $user->setCreateur($pere);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_supervisors');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/marchantRegister', name: 'app_marchant_register'),
    IsGranted("ROLE_SUPERVISEUR")]
    public function registerMarchant(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {   
        $pere = $this->getUser();

        $user = new User();
        $form = $this->createForm(marchantFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $role = $form->get('role')->getData();

            $user->setRoles($role);
            $user->setCreateur($pere);
            
            $user->setDette(0);
            $user->setMarge(0);
            $user->setMontantPris(0);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_supervisors');
        }

        return $this->render('registration/marchantRegister.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
