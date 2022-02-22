<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Achievements;
use App\Form\AchievementsType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AchievementsRepository;
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

#[Route('/achievements')]
class AchievementsController extends AbstractController
{
    #[Route('/', name: 'achievements_index', methods: ['GET'])]
    public function index(AchievementsRepository $achievementsRepository): Response
    {
        return $this->render('achievements/index.html.twig', [
            'achievements' => $achievementsRepository->findAll(),
            'title' => 'Helfopets achievements',
        ]);
    }

    #[Route('/new', name: 'achievements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, SluggerInterface $slugger,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $achievement = new Achievements();
        $document = new Document;
        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        $routeSend = 'achievements_new';
        $routeId = [];
        $this -> fastConsultationSend($request,$entityManager,$doctrine,$slugger,$imageController,$mailerController,$fast_consultation,$mailer,$fast_consultation_form,$fast_consultation_meil,$routeSend,$routeId);

        //$this -> achievementNew($request,$form, $achievement, $document, $doctrine, $entityManager, $slugger, $imageController);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
            $imageFile = $form->get('img')->getData();
            $nameDirectiry = 'img_directory';
            $setImageFile = 'setImg';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement,$setImageFile);

            for($i = 1; $i <= 3; $i++){
                $documentFile = $form->get('document'.$i)->getData();
                $getFileFile = 'getDocument'.$i;
                $setFileFile = 'setDocument'.$i;
                $nameDirectiry = 'files_directory';
                $this->deleteFiles($documentFile,$document,$getFileFile,$setFileFile,$nameDirectiry,$imageController);
                $this->uploadsImageFile($slugger,$documentFile,$nameDirectiry,$imageController,$document,$setFileFile);
            }
            
            $achievement->addDocument($document);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($document);
            $entityManager->persist($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новую запись в  блок номер 2 на странице "О нас"');
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('achievements/new.html.twig', [
            'achievement' => $achievement,
            'title' => 'Helfopets achievements new',
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'achievements_show', methods: ['GET'])]
    public function show(Achievements $achievement): Response
    {
        return $this->render('achievements/show.html.twig', [
            'achievement' => $achievement,
        ]);
    }

    #[Route('/{id}/edit', name: 'achievements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achievements $achievement, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('achievements_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        // $routeSend = 'achievements_edit';
        // $routeId = ['id' => $id];
        // $this -> fastConsultationSend($request,$entityManager,$doctrine,$slugger,$imageController,$mailerController,$fast_consultation,$mailer,$fast_consultation_form,$fast_consultation_meil,$routeSend,$routeId);
         
        $achievement = $doctrine->getRepository(Achievements::class)->findOneByIdJoinedToDocument($id);
        $document_id = $this -> documentId($achievement);
        $document = $doctrine->getRepository(Document::class)->findOneBySomeField($document_id[0]);

        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        //$this -> achievementEdition($request,$form, $achievement, $document, $doctrine, $entityManager, $slugger, $imageController);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteFiles($imageFile,$achievement,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement,$setImageFile);

            for($i = 1; $i <= 3; $i++){
                $document1File = $form->get('document'.$i)->getData();
                $getFileFile1 = 'getDocument'.$i;
                $setFileFile1 = 'setDocument'.$i;
                $nameDirectiry1 = 'files_directory';
                $this->deleteFiles($document1File,$document,$getFileFile1,$setFileFile1,$nameDirectiry1,$imageController);
                $this->uploadsImageFile($slugger,$document1File,$nameDirectiry1,$imageController,$document,$setFileFile1);
            }
            $entityManager->flush();
            $this->addFlash(
                    'success',
                    'Вы успешно отредактировали новую запись в  блоке номер 2 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('achievements/edit.html.twig', [
            'achievement' => $achievement,
            'title' => 'Helfopets achievements show',
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/delete/{id}', name: 'achievements_delete', methods: ['POST'])]
    public function delete(Request $request, Achievements $achievement,ManagerRegistry $doctrine, EntityManagerInterface $entityManager,ImageController $imageController, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $achievements_count = $doctrine->getRepository(Achievements::class)->countFindAllAchievements();
            if($achievements_count <= 1){
                $this->addFlash(
                    'success',
                    'Вы не можете удалить карточку, должно остаться не менее одной карточки в разделе!'); 
                return $this->redirectToRoute('about_comtroller',[], Response::HTTP_SEE_OTHER);
            }
            $achievement = $doctrine->getRepository(Achievements::class)->findOneByIdJoinedToDocument($id);
            $document_id = $this -> documentId($achievement);
            $documents = $doctrine->getRepository(Document::class)->findOneBySomeField($document_id[0]);
            
            $this -> deleteAllFile($documents,$imageController);

            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteImgFiles($achievement,$getImageFile,$setImageFile,$nameDirectiry,$imageController);

            $entityManager->remove($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили запись из  блока номер 2 на странице "О нас"'); 
        }
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }

    private function deleteAllFile($documents,$imageController){
        for($i = 1; $i <= 3; $i++){
                $getFileFile = 'getDocument'.$i;
                $setFileFile = 'setDocument'.$i;
                $nameDirectiry = 'files_directory';
                $this->deleteImgFiles($documents,$getFileFile,$setFileFile,$nameDirectiry,$imageController);
            }
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

    private function documentId($achievement){
        $document_id = [];
        foreach($achievement->getDocument() as $document){
            $document_id[] = $document->getId(); 
        }
        return $document_id;
    }

    private function fastConsultationSend($request,$entityManager,$doctrine,$slugger,$imageController,$mailerController,$fast_consultation,$mailer,$fast_consultation_form,$fast_consultation_meil,$routeSend,$routeId){
        
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute($routeSend, $routeId, Response::HTTP_SEE_OTHER);
        }
    }

    private function achievementEdition($request, $form, $achievement, $document, $doctrine, $entityManager, $slugger, $imageController)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteFiles($imageFile,$achievement,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement,$setImageFile);

            for($i = 1; $i <= 3; $i++){
                $document1File = $form->get('document'.$i)->getData();
                $getFileFile1 = 'getDocument'.$i;
                $setFileFile1 = 'setDocument'.$i;
                $nameDirectiry1 = 'files_directory';
                $this->deleteFiles($document1File,$document,$getFileFile1,$setFileFile1,$nameDirectiry1,$imageController);
                $this->uploadsImageFile($slugger,$document1File,$nameDirectiry1,$imageController,$document,$setFileFile1);
            }
            
            $entityManager->flush();
            $this->addFlash(
                    'success',
                    'Вы успешно отредактировали новую запись в  блоке номер 2 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        
    }

    private function achievementNew($request,$form, $achievement, $document, $doctrine, $entityManager, $slugger, $imageController)
    {

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
            $imageFile = $form->get('img')->getData();
            $nameDirectiry = 'img_directory';
            $setImageFile = 'setImg';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement,$setImageFile);

            for($i = 1; $i <= 3; $i++){
                $documentFile = $form->get('document'.$i)->getData();
                $getFileFile = 'getDocument'.$i;
                $setFileFile = 'setDocument'.$i;
                $nameDirectiry = 'files_directory';
                $this->deleteFiles($documentFile,$document,$getFileFile,$setFileFile,$nameDirectiry,$imageController);
                $this->uploadsImageFile($slugger,$documentFile,$nameDirectiry,$imageController,$document,$setFileFile);
            }
            
            $achievement->addDocument($document);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($document);
            $entityManager->persist($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новую запись в  блок номер 2 на странице "О нас"');
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        
    }

}
