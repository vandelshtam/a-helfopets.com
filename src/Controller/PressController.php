<?php

namespace App\Controller;

use App\Entity\Press;
use App\Form\PressType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Repository\PressRepository;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/press')]
class PressController extends AbstractController
{
    #[Route('/', name: 'press_index', methods: ['GET'])]
    
    public function index(PressRepository $pressRepository,Request $request): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->render('press/index.html.twig', [
            'presses' => $pressRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'press_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('press_new', [], Response::HTTP_SEE_OTHER);
        }

        $press = new Press();
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $nameDirectiry = 'img_directory';
            $setImageFile = 'setImg';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$press,$setImageFile);
            $entityManager->persist($press);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новый слайдер в блоке "пресса о нас" на странице "О нас"'); 
            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/new.html.twig', [
            'press' => $press,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'press_show', methods: ['GET'])]
    public function show(Press $press,Request $request,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->render('press/show.html.twig', [
            'press' => $press,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'press_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Press $press, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer,int $id): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('press_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteFiles($imageFile,$press,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$press,$setImageFile);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно отредактировали слайдер в  блоке "пресса о нас" на странице "О нас"'); 
            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/edit.html.twig', [
            'press' => $press,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'press_delete', methods: ['POST'])]
    public function delete(Request $request, Press $press, EntityManagerInterface $entityManager,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$press->getId(), $request->request->get('_token'))) {
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this -> deleteImgFiles($press,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($press);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили информацию из  блока "пресса о нас" на странице "О нас"'); 
        }

        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
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
