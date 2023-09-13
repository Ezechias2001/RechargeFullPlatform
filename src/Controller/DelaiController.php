<?php

namespace App\Controller;

use App\Entity\Delai;
use App\Form\DelaiType;
use App\Repository\DelaiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/delai')]
class DelaiController extends AbstractController
{
    #[Route('/', name: 'app_delai_index', methods: ['GET'])]
    public function index(DelaiRepository $delaiRepository): Response
    {
        return $this->render('delai/index.html.twig', [
            'delais' => $delaiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_delai_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $delai = new Delai();
        $form = $this->createForm(DelaiType::class, $delai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($delai);
            $entityManager->flush();

            return $this->redirectToRoute('app_delai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('delai/new.html.twig', [
            'delai' => $delai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delai_show', methods: ['GET'])]
    public function show(Delai $delai): Response
    {
        return $this->render('delai/show.html.twig', [
            'delai' => $delai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_delai_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Delai $delai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DelaiType::class, $delai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_delai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('delai/edit.html.twig', [
            'delai' => $delai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delai_delete', methods: ['POST'])]
    public function delete(Request $request, Delai $delai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$delai->getId(), $request->request->get('_token'))) {
            $entityManager->remove($delai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delai_index', [], Response::HTTP_SEE_OTHER);
    }
}
