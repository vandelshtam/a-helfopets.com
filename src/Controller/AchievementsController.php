<?php

namespace App\Controller;

use App\Entity\Achievements;
use App\Form\AchievementsType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AchievementsRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
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
        ]);
    }

    #[Route('/new', name: 'achievements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController): Response
    {
        $achievement = new Achievements();
        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            
            $nameDirectiry = 'img_directory';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement);
        
            $entityManager->persist($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новую запись в  блок номер 2 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achievements/new.html.twig', [
            'achievement' => $achievement,
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
    public function edit(Request $request, Achievements $achievement, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this -> deleteImageFiles($imageFile,$achievement,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement);
            
            $entityManager->flush();
            $this->addFlash(
                    'success',
                    'Вы успешно отредактировали новую запись в  блоке номер 2 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('achievements/edit.html.twig', [
            'achievement' => $achievement,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'achievements_delete', methods: ['POST'])]
    public function delete(Request $request, Achievements $achievement, EntityManagerInterface $entityManager,ImageController $imageController): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $getImageFile = 'getImg';
            $setImageFile = 'setImg';
            $nameDirectiry = 'img_directory';
            $this->deleteFiles($achievement,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили запись из  блока номер 2 на странице "О нас"'); 
        }
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }
    private function uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$achievement){
        if ($imageFile) {
            $newFilename = $imageController->uploadNewFileName($slugger, $imageFile,$nameDirectiry);
            $achievement->setImg($newFilename);
        }
    }
    private function deleteImageFiles($imageFile,$nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
        if ($imageFile){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
        }
    } 
    private function deleteFiles($nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
    }       
}
