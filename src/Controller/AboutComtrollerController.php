<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Review;

use App\Entity\Fotorev;

use App\Form\RatingType;
use App\Form\ReviewType;

use App\Entity\Fotoreview;
use App\Entity\OurMission;
use Doctrine\ORM\Mapping\Id;
use App\Entity\FastConsultation;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use App\Form\FastConsultationType;
use App\Repository\PressRepository;
use App\Repository\RatingRepository;
use App\Repository\ReviewRepository;
use App\Repository\OurMissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AchievementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AboutComtrollerController extends AbstractController
{
    #[Route('/about/comtroller', name: 'about_comtroller')]
    public function index(Request $request,OurMissionRepository $ourMissionRepository,AchievementsRepository $achievementsRepository,PressRepository $pressRepository,RatingRepository $ratingRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,SluggerInterface $slugger,ReviewRepository $reviewRepository): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        $fotoreview = new Fotoreview;
        $fotoreview2 = new Fotoreview;
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('fotorev')->getData();
            $image2File = $form->get('fotorev2')->getData();
            if ($imageFile) {
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
                $fotoreview->setFoto($newFilename);
                
            }
            if ($image2File) {
                $originalFilename2 = pathinfo($image2File->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename2 = $slugger->slug($originalFilename2);
                $newFilename2 = $safeFilename2.'-'.uniqid().'.'.$image2File->guessExtension();
                
                try {
                    $image2File->move(
                        $this->getParameter('img_directory'),
                        $newFilename2
                    );
                } catch (FileException $e) {
                    echo "An error occurred while creating your directory at ";
                }
                $fotoreview2->setFoto($newFilename2);
                
            }
            $review->addFotoreview($fotoreview);
            $review->addFotoreview($fotoreview2);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($fotoreview);
            $entityManager->persist($fotoreview2);
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Спасибо за отзыв!'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        // $product = $doctrine->getRepository(Rating::class)->findAll();
        $rating_all = $ratingRepository->findAll();
        // $rating_all_grade = $ratingRepository->findAllGrade();
        $rating = $ratingRepository->findAllRating();

        $summ_rating = 0;
        foreach($rating_all as $elem){
            $summ_rating += $elem->getGrade();
        }
        $rating_value = round(($summ_rating/$rating), 0, PHP_ROUND_HALF_DOWN);
        
        return $this->renderForm('about_comtroller/index.html.twig', [
            'controller_name' => 'AboutComtrollerController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'our_missions' => $ourMissionRepository->findAll(),
            'achievements' => $achievementsRepository->findAll(),
            'presses' => $pressRepository->findAll(),
            'rating_value' => $rating_value,
            'form' => $form,
            'reviews' => $reviewRepository->findAll(),
        ]);
    }
}
