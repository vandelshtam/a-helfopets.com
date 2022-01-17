<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Entity\Fotoblog;
use App\Entity\Ratingblog;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\BlogRepository;
use App\Controller\ImageController;
use App\Controller\MailerController;
use App\Repository\RatingblogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index', methods: ['GET'])]
    public function index(Request $request,BlogRepository $blogRepository, ManagerRegistry $doctrine,EntityManagerInterface $entityManager,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger, ManagerRegistry $doctrine,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fotoblog = new Fotoblog();
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $imageFile2 = $form->get('foto2')->getData();

            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$blog,$setImageFile);
            
            $setImageFile2 = 'setFoto';
            $nameDirectiry2 = 'galery_directory';
            $this->uploadsImageFile($slugger,$imageFile2,$nameDirectiry2,$imageController,$fotoblog,$setImageFile2);

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
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('blog_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'blog_show', methods: ['GET'])]
    public function show(Request $request,EntityManagerInterface $entityManager,Blog $blog,ManagerRegistry $doctrine, int $id, RatingblogRepository $ratingblogRepository,RatingblogController $ratingBlog,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->findOneByIdJoinedToFotoblog($id);
        $ratingId = $doctrine->getRepository(Ratingblog::class)->findBy([
            'blog' => $id,    
        ]);
        $ratingSummBlog = $ratingBlog->ratingSummBlog($ratingId);
        $ratingCountBlog = $ratingBlog->ratingCountBlog($ratingId);
        $rating_value = $ratingBlog -> ratingBlog($ratingSummBlog,$ratingCountBlog);
        
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('blog_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('blog/show.html.twig', [
            'blog' => $blog,
            'id' => $id,
            'rating_value' => $rating_value,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager,ImageController $imageController,SluggerInterface $slugger,ManagerRegistry $doctrine, int $id,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->findOneByIdJoinedToFotoblog($id);
        $fotoblog_id = [];
        foreach($blog->getFotoblog() as $fotoblog){
            $fotoblog_id[] = $fotoblog->getId(); 
        }
        $fotoblog = $doctrine->getRepository(Fotoblog::class)->findOneBySomeField($fotoblog_id[0]);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        $imageFile = $form->get('foto')->getData();
        $imageFile2 = $form->get('foto2')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $getImageFile = 'getFoto';
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($imageFile,$blog,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$blog,$setImageFile);

            $getImageFile2 = 'getFoto';
            $setImageFile2 = 'setFoto';
            $nameDirectiry2 = 'galery_directory';
            $this->deleteFiles($imageFile2,$fotoblog,$getImageFile2,$setImageFile2,$nameDirectiry2,$imageController);
            $this->uploadsImageFile($slugger,$imageFile2,$nameDirectiry2,$imageController,$fotoblog,$setImageFile2);

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили  пост!'); 
            return $this->redirectToRoute('blog_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('blog_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager,int $id, ManagerRegistry $doctrine,ImageController $imageController): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
    
            $fotoblogs = $blog -> getFotoblog();
            $ratingblogs = $blog -> getRatingblog();
            $this -> deleteAllGaleryFileFotoblog($fotoblogs,$entityManager);
            $getImageFile = 'getFoto';
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteImgFiles($blog,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($blog);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили пост!'); 
        }
        return $this->redirectToRoute('blog_index', [], Response::HTTP_SEE_OTHER);
    }
   
    private function deleteAllGaleryFileFotoblog($fotoblogs,$entityManager){
        $filesystem = new Filesystem();
        foreach ($fotoblogs as $fotoblogFoto){
            if($fotoblogFoto->getFoto() != null){
                $fotoblogFoto->setFoto(
                    $path_galery = new File($this->getParameter('galery_directory').'/'.$fotoblogFoto->getFoto())
                );
                $filesystem->remove(['symlink', $path_galery, $fotoblogFoto->getFoto()]);
            }
            $entityManager->remove($fotoblogFoto);
        }
    }
    private function uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$article,$setImageFile){
        if ($imageFile) {
            $newFilename = $imageController->uploadNewFileName($slugger,$imageFile,$nameDirectiry);
            $article->$setImageFile($newFilename);
        }
    }
    private function deleteFiles($imageFile,$nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
        if ($imageFile){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
        }
    }
    private function deleteImgFiles($nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
    } 
}
