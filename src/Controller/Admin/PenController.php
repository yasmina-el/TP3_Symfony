<?php

namespace App\Controller\Admin;

use App\Entity\Pen;
use App\Form\PenType;
use App\Repository\PenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/pen')]
class PenController extends AbstractController
{
    #[Route('/', name: 'app_admin_pen_index', methods: ['GET'])]
    public function index(PenRepository $penRepository): Response
    {
        return $this->render('admin/pen/index.html.twig', [
            'pens' => $penRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_pen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pen = new Pen();
        $form = $this->createForm(PenType::class, $pen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pen);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_pen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/pen/new.html.twig', [
            'pen' => $pen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_pen_show', methods: ['GET'])]
    public function show(Pen $pen): Response
    {
        return $this->render('admin/pen/show.html.twig', [
            'pen' => $pen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_pen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pen $pen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PenType::class, $pen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_pen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/pen/edit.html.twig', [
            'pen' => $pen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_pen_delete', methods: ['POST'])]
    public function delete(Request $request, Pen $pen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pen->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_pen_index', [], Response::HTTP_SEE_OTHER);
    }
}
