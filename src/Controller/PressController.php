<?php

namespace App\Controller;

use App\Entity\Press;
use App\Form\PressType;
use App\Repository\PressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/press')]
class PressController extends AbstractController
{
    #[Route('/', name: 'press_index', methods: ['GET'])]
    public function index(PressRepository $pressRepository): Response
    {
        return $this->render('press/index.html.twig', [
            'presses' => $pressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'press_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $press = new Press();
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($press);
            $entityManager->flush();

            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/new.html.twig', [
            'press' => $press,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'press_show', methods: ['GET'])]
    public function show(Press $press): Response
    {
        return $this->render('press/show.html.twig', [
            'press' => $press,
        ]);
    }

    #[Route('/{id}/edit', name: 'press_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Press $press, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/edit.html.twig', [
            'press' => $press,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'press_delete', methods: ['POST'])]
    public function delete(Request $request, Press $press, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$press->getId(), $request->request->get('_token'))) {
            $entityManager->remove($press);
            $entityManager->flush();
        }

        return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
    }
}
