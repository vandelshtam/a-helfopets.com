<?php

namespace App\Controller;

use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'rating')]
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }

    #[Route('/rating/new', name: 'rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        
        $value = $request->request->get('ratin');
        $entityManager = $doctrine->getManager();
        $rating = new Rating();
        $rating->setGrade($value);
    
        $entityManager->persist($rating);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Вы успешно оценили нашу деятельность'); 
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        
    }
}
