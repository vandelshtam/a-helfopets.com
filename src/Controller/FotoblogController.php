<?php

namespace App\Controller;

use App\Entity\Fotoblog;
use App\Form\FotoblogType;
use App\Entity\FastConsultation;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Repository\FotoblogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, int $id,SluggerInterface $slugger,MailerInterface $mailer,ImageController $imageController): Response
    {
        $fotoblog = new Fotoblog();
        $form = $this->createForm(FotoblogType::class, $fotoblog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$fotoblog,$setImageFile);
            
            $entityManager->persist($fotoblog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новый слайд к посту!'); 
            return $this->redirectToRoute('blog_show', ['id'=> $id], Response::HTTP_SEE_OTHER);
        }
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $this -> fast_consultation($request, $entityManager,$mailer,$fast_consultation); 
            return $this->redirectToRoute('blog_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fotoblog/new.html.twig', [
            'fotoblog' => $fotoblog,
            'form' => $form,
            'id' => $id,
            'fast_consultation_form' => $fast_consultation_form,
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
    public function edit(Request $request, Fotoblog $fotoblog, EntityManagerInterface $entityManager,SluggerInterface $slugger,MailerInterface $mailer,ImageController $imageController): Response
    {
        $form = $this->createForm(FotoblogType::class, $fotoblog);
        $form->handleRequest($request);
        $blog_id = $fotoblog->getBlog()->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $getImageFile = 'getFoto';
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($imageFile,$fotoblog,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$fotoblog,$setImageFile);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили слайдер  поста!'); 
            return $this->redirectToRoute('blog_show', ['id'=> $blog_id], Response::HTTP_SEE_OTHER);
        }
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $this -> fast_consultation($request, $entityManager,$mailer,$fast_consultation); 
            return $this->redirectToRoute('blog_show', ['id'=> $blog_id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fotoblog/edit.html.twig', [
            'fotoblog' => $fotoblog,
            'form' => $form,
            'id' => $blog_id,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/delete/{id}', name: 'fotoblog_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Fotoblog $fotoblog, EntityManagerInterface $entityManager,ImageController $imageController): Response
    {
        $blog_id = $fotoblog->getBlog()->getId();
        if ($this->isCsrfTokenValid('delete'.$fotoblog->getId(), $request->request->get('_token'))) {
            $getImageFile = 'getFoto';
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteImgFiles($fotoblog,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($fotoblog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно слайдер поста!'); 
        }
        return $this->redirectToRoute('blog_show', ['id'=> $blog_id], Response::HTTP_SEE_OTHER);
    }

    private function uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$article,$setImageFile){
        if ($imageFile) {
            $newFilename = $imageController->uploadNewFileName($slugger,$imageFile,$nameDirectiry);
            $article->$setImageFile($newFilename);
        }
    }
    private function deleteFiles($imageFile,$nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
        if ($imageFile){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
        }
    }
    private function deleteImgFiles($nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
    } 
}
