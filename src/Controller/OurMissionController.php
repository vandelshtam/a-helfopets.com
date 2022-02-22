<?php

namespace App\Controller;

use App\Entity\OurMission;
use App\Form\OurMissionType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Controller\MailerController;
use App\Repository\OurMissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/our/mission')]
class OurMissionController extends AbstractController
{
    #[Route('/', name: 'our_mission_index', methods: ['GET'])]
    public function index(OurMissionRepository $ourMissionRepository,Request $request,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('our_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('our_mission/index.html.twig', [
            'our_missions' => $ourMissionRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'Helfopets our mission',
        ]);
    }

    #[Route('/new', name: 'our_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('our_mission_new', [], Response::HTTP_SEE_OTHER);
        }

        $ourMission = new OurMission();
        $form = $this->createForm(OurMissionType::class, $ourMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $nameDirectiry = 'img_directory';
            $setImageFile = 'setImg';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$ourMission,$setImageFile);
            $entityManager->persist($ourMission);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно заполнили блок номер 1 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('our_mission/new.html.twig', [
            'our_mission' => $ourMission,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'Helfopets our mission new',
        ]);
    }

    #[Route('/{id}', name: 'our_mission_show', methods: ['GET'])]
    public function show(OurMission $ourMission,Request $request,EntityManagerInterface $entityManager,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('our_mission_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('our_mission/show.html.twig', [
            'our_mission' => $ourMission,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'Helfopets our mission show',
        ]);
    }

    #[Route('/{id}/edit', name: 'our_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OurMission $ourMission, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('our_mission_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(OurMissionType::class, $ourMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteFiles($imageFile,$ourMission,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$ourMission,$setImageFile);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно отредактировали блок номер 1 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('our_mission/edit.html.twig', [
            'our_mission' => $ourMission,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'Helfopets our mission edit',
        ]);
    }

    #[Route('/delete/{id}', name: 'our_mission_delete', methods: ['POST'])]
    public function delete(Request $request, OurMission $ourMission, EntityManagerInterface $entityManager,ImageController $imageController): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$ourMission->getId(), $request->request->get('_token'))) {
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this -> deleteImgFiles($ourMission,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($ourMission);
            $entityManager->flush();
            $this->addFlash(
            'success',
            'Вы успешно информацию из  блока номер 1 на странице "О нас"'); 
        }
        
        return $this->redirectToRoute('our_mission_index', [], Response::HTTP_SEE_OTHER);
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
