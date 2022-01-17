<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Entity\Fotoreview;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Repository\PressRepository;
use App\Controller\MailerController;
use App\Controller\RatingController;
use App\Repository\RatingRepository;
use App\Repository\ReviewRepository;
use App\Repository\FotoreviewRepository;
use App\Repository\OurMissionRepository;
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

class AboutComtrollerController extends AbstractController
{
    #[Route('/about/comtroller', name: 'about_comtroller')]
    public function index(Request $request,OurMissionRepository $ourMissionRepository,FotoreviewRepository $fotoreviewRepository, AchievementsRepository $achievementsRepository,PressRepository $pressRepository,RatingRepository $ratingRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,SluggerInterface $slugger,ReviewRepository $reviewRepository, ImageController $imageController,RatingController $ratingController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

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
            
            $nameDirectiry = 'img_directory';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$fotoreview);
            
            $nameDirectiry = 'img_directory';
            $this->uploadsImageFile($slugger,$imageFile2,$nameDirectiry,$imageController,$fotoreview);
                
            $nameDirectiry = 'img_directory';
            $this->uploadsImageFile($slugger,$imageFile3,$nameDirectiry,$imageController,$fotoreview);
                
            $review->setIp($localIP);
            
            $review->addFotoreview($fotoreview);
            $review->addFotoreview($fotoreview2);
            $review->addFotoreview($fotoreview3);
            // $review->setReview(0);
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
        $rating = $ratingRepository->findAllRating();
        $ratingSumm = $ratingController->ratingSumm($ratingRepository);
        $rating_value = $ratingController->rating($ratingSumm,$rating);
        return $this->renderForm('about_comtroller/index.html.twig', [
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

    private function uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$fotoreview){
        if ($imageFile) {
            $newFilename = $imageController->uploadNewFileName($slugger, $imageFile,$nameDirectiry);
            $fotoreview->setFoto($newFilename);
        }
    }
}