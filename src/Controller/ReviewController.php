<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Entity\Fotoreview;
use App\Repository\ReviewRepository;

use App\Repository\FotoreviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/', name: 'review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, FotoreviewRepository $fotoreviewRepository,SluggerInterface $slugger,ReviewRepository $reviewRepository)
    {
        $localIP = getHostByName(getHostName());
        $answer = new Answer();
        $review = new Review();
        $fotoreview = new Fotoreview;
        
        $value = $request->request->get('text');
        $name = $request->request->get('name');
        $answer_name = $request->request->get('answer_name');
        $id = $request->request->get('answer_id');
        $answer_text = $request->request->get('answer_text');
        $answer->setText($answer_text);
        $answer->setName($answer_name);
        $answer->setAnswerId($id);
        $review->setIp($localIP);
        $review->addFotoreview($fotoreview);
        $entityManager->persist($fotoreview);
        $review->addAnswer($answer);
        $entityManager->persist($answer);
        $entityManager = $doctrine->getManager();
        $review->setText($value);
        $review->setAnswerId($id);
        $review->setName($name);
        $entityManager->persist($review);
        $entityManager->flush();

        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);    
    }

    #[Route('/{id}', name: 'review_show', methods: ['GET'])]
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager->flush();
        
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Отзыв удален'); 
        }
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/bannedToggle', name: 'edit_banned_toggle', methods: ['GET', 'POST'])]
    public function bannedToggle(Request $request, Review $review, EntityManagerInterface $entityManager,int $id,ReviewRepository $reviewRepository): Response
    {
        $banned = $reviewRepository->find($id)->getBanned();
        if($banned == 1){
            $value = 0;
        }
        else{
            $value = 1;
        }      
        $review->setBanned($value);
        $entityManager->persist($review);
        $entityManager->flush();
        if($value == 1){
            $this->addFlash(
                'success',
                'Отзыв заблокирован!'); 
        }
        else{
            $this->addFlash(
                'success',
                'Отзыв разблокирован!'); 
        }
        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }
}
