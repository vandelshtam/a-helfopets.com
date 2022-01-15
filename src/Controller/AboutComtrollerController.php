<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Entity\Fotoreview;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Repository\PressRepository;
use App\Repository\RatingRepository;
use App\Repository\ReviewRepository;
use App\Repository\FotoreviewRepository;
use App\Repository\OurMissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AchievementsRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AboutComtrollerController extends AbstractController
{
    #[Route('/about/comtroller', name: 'about_comtroller')]
    public function index(Request $request,OurMissionRepository $ourMissionRepository,FotoreviewRepository $fotoreviewRepository, AchievementsRepository $achievementsRepository,PressRepository $pressRepository,RatingRepository $ratingRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,SluggerInterface $slugger,ReviewRepository $reviewRepository, ImageController $imageController): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $reviews_all = $reviewRepository->findAll();
        
        $reviews = [];
        foreach($reviews_all as $elem){   
            $reviews[] = $doctrine->getRepository(Review::class)->findOneByIdJoinedToFotoreview($elem->getId());
        }
        
	    $localIP = getHostByName(getHostName());

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        $fotoreview = new Fotoreview;
        $fotoreview2 = new Fotoreview;
        $fotoreview3 = new Fotoreview;
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $imageFile2 = $form->get('foto2')->getData();
            $imageFile3 = $form->get('foto3')->getData();
            if ($imageFile) {
                $nameDirectiry = 'img_directory';
                $newFilename = $imageController->uploadNewFileName($slugger, $imageFile,$nameDirectiry);
                $fotoreview->setFoto($newFilename);
            }
            if ($imageFile2) {
                $nameDirectiry = 'img_directory';
                $newFilename2 = $imageController->uploadNewFileName($slugger, $imageFile2,$nameDirectiry);
                $fotoreview2->setFoto($newFilename2);
            }
            if ($imageFile3) {
                $nameDirectiry = 'img_directory';
                $newFilename3 = $imageController->uploadNewFileName($slugger, $imageFile3,$nameDirectiry);
                $fotoreview3->setFoto($newFilename3);
            }
            
            $review->setIp($localIP);
            
            $review->addFotoreview($fotoreview);
            $review->addFotoreview($fotoreview2);
            $review->addFotoreview($fotoreview3);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($fotoreview);
            $entityManager->persist($fotoreview2);
            $entityManager->persist($fotoreview3);
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Спасибо за отзыв!'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        $rating_all = $ratingRepository->findAll();
        $rating = $ratingRepository->findAllRating();
        $summ_rating = 0;
        foreach($rating_all as $elem){
            $summ_rating += $elem->getGrade();
        }
        if($rating == null){
            $rating_value = 0;
        }
        else{
            $rating_value = round(($summ_rating/$rating), 0, PHP_ROUND_HALF_DOWN);
        }
        return $this->renderForm('about_comtroller/index.html.twig', [
            'controller_name' => 'AboutComtrollerController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'our_missions' => $ourMissionRepository->findAll(),
            'achievements' => $achievementsRepository->findAll(),
            'presses' => $pressRepository->findAll(),
            'rating_value' => $rating_value,
            'form' => $form,
            'reviews' => $reviews,
            'ip' => $localIP,
        ]);
    }
}