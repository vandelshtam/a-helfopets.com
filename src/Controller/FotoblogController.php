<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Fotoblog;
use App\Form\FotoblogType;
use App\Repository\FotoblogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/fotoblog')]
class FotoblogController extends AbstractController
{
    #[Route('/', name: 'fotoblog_index', methods: ['GET'])]
    public function index(FotoblogRepository $fotoblogRepository): Response
    {
        return $this->render('fotoblog/index.html.twig', [
            'fotoblogs' => $fotoblogRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'fotoblog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, int $id,SluggerInterface $slugger): Response
    {
        $fotoblog = new Fotoblog();
        $form = $this->createForm(FotoblogType::class, $fotoblog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $fotoblog->setFoto($newFilename);
            }
            $entityManager->persist($fotoblog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новый слайд к посту!'); 
            return $this->redirectToRoute('blog_show', ['id'=> $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fotoblog/new.html.twig', [
            'fotoblog' => $fotoblog,
            'form' => $form,
            'id' => $id,
        ]);
    }

    #[Route('/{id}', name: 'fotoblog_show', methods: ['GET'])]
    public function show(Fotoblog $fotoblog): Response
    {
        return $this->render('fotoblog/show.html.twig', [
            'fotoblog' => $fotoblog,
        ]);
    }

    #[Route('/{id}/edit', name: 'fotoblog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fotoblog $fotoblog, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FotoblogType::class, $fotoblog);
        $form->handleRequest($request);
        $blog_id = $fotoblog->getBlog()->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $fotoblog->setFoto($newFilename);
            }
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили слайдер  поста!'); 
            return $this->redirectToRoute('blog_show', ['id'=> $blog_id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fotoblog/edit.html.twig', [
            'fotoblog' => $fotoblog,
            'form' => $form,
            'id' => $blog_id,
        ]);
    }

    #[Route('/{id}', name: 'fotoblog_delete', methods: ['POST'])]
    public function delete(Request $request, Fotoblog $fotoblog, EntityManagerInterface $entityManager): Response
    {
        $blog_id = $fotoblog->getBlog()->getId();
        if ($this->isCsrfTokenValid('delete'.$fotoblog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fotoblog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно слайдер поста!'); 
        }

        return $this->redirectToRoute('blog_show', ['id'=> $blog_id], Response::HTTP_SEE_OTHER);
    }

    private function uploadNewFileName(SluggerInterface $slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                
        try {
            $imageFile->move(
                $this->getParameter('galery_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }
}
