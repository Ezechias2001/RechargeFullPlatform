<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/user/profile/modify', name: 'app_modify', methods: ["GET","POST"] ),
    IsGranted('ROLE_USER')
    ]
    public function modifyProfile(Request $request,UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {  
        $user = $this->getUser(); 

        $fullName = $request->request->get('fullName');
        if ($fullName) {
            $user->setFullName($fullName);
        }

        $telephone = $request->request->get('telephone');
        if ($telephone) {
            $user->setPhoneNumber($telephone);
        }

        $agence = $request->request->get('agence');
        if ($agence) {
            $user->setAgence($agence);
        }
        $email = $request->request->get('email');
        if ($email) {
            $user->setEmail($email);
        }
        
        $oldPassword = $request->request->get('password');
        $newPassword = $request->request->get('newPassword');
        $confirmPassword = $request->request->get('confirmPassword');

        if ($oldPassword && $passwordEncoder->isPasswordValid($user, $oldPassword)) {
            if ($newPassword == $confirmPassword ) {
                $encodedPassword = $passwordEncoder->hashPassword($user, $newPassword);
                $user->setPassword($encodedPassword);
            }
        }

        $image = $request->request->get('image')->getData(); 
        dd($image);
        $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setBrochureFilename($newFilename);
            }

        
        

        dd($user);


        $entityManager->persist($user);
        $entityManager->flush();
       

        $roles = $user->getRoles(); // Le rôle que vous souhaitez rechercher
        return $this->render('user/profile/index.html.twig', [
            'role' => $roles[0],
        ]);
    }
}
