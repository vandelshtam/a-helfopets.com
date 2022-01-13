<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Entity\Fotoblog;
use App\Entity\Ratingblog;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\BlogRepository;
use App\Repository\RatingblogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index', methods: ['GET'])]
    public function index(Request $request,BlogRepository $blogRepository, ManagerRegistry $doctrine,EntityManagerInterface $entityManager): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->renderForm('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $fotoblog = new Fotoblog();
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $imageFile2 = $form->get('foto2')->getData();
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $blog->setFoto($newFilename);
            }
            if ($imageFile2) {
                $newFilename2 = $this->uploadNewFileName($slugger, $imageFile2);
                $fotoblog->setFoto($newFilename2);
            }
            $titleslider = $form->get('titleslider')->getData();
            $descriptionslider = $form->get('descriptionslider')->getData();
            $linkslider = $form->get('linkslider')->getData();
            $fotoblog->setTitle($titleslider);
            $fotoblog->setDescription($descriptionslider);
            $fotoblog->setLink($linkslider);
            $blog->addFotoblog($fotoblog);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($fotoblog);
            $entityManager->persist($blog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новый пост!'); 
            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'blog_show', methods: ['GET'])]
    public function show(Request $request,Blog $blog,ManagerRegistry $doctrine, int $id,RatingblogRepository $ratingblogRepository): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->findOneByIdJoinedToFotoblog($id);
        $ratingId = $doctrine->getRepository(Ratingblog::class)->findBy([
            'blog' => $id,    
        ]);
        $summ_ratingblog = 0;
        $ratingblog_count = 0;
        foreach($ratingId as $elem){
            $summ_ratingblog += $elem->getRating();
        }
        foreach($ratingId as $elem){
            $ratingblog_count += 1;
        }
        if($ratingblog_count != null){
            $rating_value = round(($summ_ratingblog/$ratingblog_count), 2, PHP_ROUND_HALF_DOWN);
        }
        else{
            $rating_value = 0;
        }
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->renderForm('blog/show.html.twig', [
            'blog' => $blog,
            'id' => $id,
            'rating_value' => $rating_value,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили  пост!'); 
            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили пост!'); 
        }
        return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
    }

    private function uploadNewFileName(SluggerInterface $slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);       
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();        
        try {
            $imageFile->move(
                $this->getParameter('galery_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }    
}
