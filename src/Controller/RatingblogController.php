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
        //dd($value);
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
    public function ratingSummBlog($ratingId){
        $summ_ratingblog = 0;
        foreach($ratingId as $elem){
            $summ_ratingblog += $elem->getRating();
        }
        return $summ_ratingblog;
    }
    public function ratingCountBlog($ratingId){
        $ratingblog_count = 0;
        foreach($ratingId as $elem){
            $ratingblog_count += 1;
        }
        return $ratingblog_count;
    }
    public function ratingBlog($ratingSumm, $ratingCount){
        if($ratingCount != null){
            $rating_value = round(($ratingSumm/$ratingCount), 2, PHP_ROUND_HALF_DOWN);
        }
        else{
            $rating_value = 0;
        }
        return $rating_value;
    }
}
