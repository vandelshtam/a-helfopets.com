<?php

namespace App\Controller;

use App\Entity\Achievements;
use App\Form\AchievementsType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
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
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $achievement = new Achievements();
        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            if ($imageFile) {
                $newFilename = $this -> uploadNewFileName($slugger, $imageFile);
                $achievement->setImg($newFilename);
            }

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
    public function edit(Request $request, Achievements $achievement, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(AchievementsType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            if ($imageFile) {
                $this -> deleteImageFile($achievement);
                $newFilename = $this -> uploadNewFileName($slugger, $imageFile);
                $achievement->setImg($newFilename);
            }
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
    public function delete(Request $request, Achievements $achievement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $this -> deleteImageFile($achievement);
            $entityManager->remove($achievement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили запись из  блока номер 2 на странице "О нас"'); 
        }
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }

    private function uploadNewFileName($slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);       
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();       
        try {
            $imageFile->move(
                        $this->getParameter('img_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }
        return $newFilename;   
    }
    private function deleteImageFile($achievement){
        $filesystem = new Filesystem();
        if($achievement->getImg() != null){
            $achievement->setImg(
                $path = new File($this->getParameter('img_directory').'/'.$achievement->getImg())
            );
            $filesystem->remove(['symlink', $path, $achievement->getImg()]);
        }
    }    
}
