<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stagiaire')]
class StagiaireController extends AbstractController
{
    #[Route('/', name: 'app_stagiaire_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stagiaire_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stagiaire/new.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stagiaire_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]

    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stagiaire_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]

    public function edit(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stagiaire_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]

    public function delete(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stagiaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stagiaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
