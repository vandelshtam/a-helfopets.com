<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Ratingblog;
use App\Repository\RatingRepository;
use App\Repository\RatingblogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingblogController extends AbstractController
{
    #[Route('/ratingblog', name: 'ratingblog')]
    public function index(): Response
    {
        return $this->render('ratingblog/index.html.twig', [
            'controller_name' => 'RatingblogController',
        ]);
    }

    #[Route('/ratingblog/new/{id}', name: 'ratingblog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, RatingblogRepository $ratingblogRepository, int $id): Response
    {
        $localIP = getHostByName(getHostName());
        $blog = $doctrine->getRepository(Blog::class)->find($id);
        $ratingIp = $doctrine->getRepository(Ratingblog::class)->findOneBy([
            'blog' => $id,    
        ]);
        if($ratingIp != null){
            if($ratingIp->getIp() != null){
                            $this->addFlash(
                                'success',
                                'Извините! Вы можете дать вашу оценку только один раз.'); 
                            return $this->redirectToRoute('blog_show', ['id' => $id], Response::HTTP_SEE_OTHER);    
                        }
        }
            
        $value = $request->request->get('rating');
        $entityManager = $doctrine->getManager();
        $ratingblog = new Ratingblog();
        $ratingblog->setRating($value);
        $ratingblog->setIp($localIP);
        $blog->addRatingblog($ratingblog);
        $entityManager->persist($ratingblog);
        $entityManager->persist($blog);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Спасибо за вашу оценку'); 
        return $this->redirectToRoute('blog_show', ['id' => $id], Response::HTTP_SEE_OTHER);    
    }
}
