<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\FileUploader;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Controller\MailerController;
use App\Repository\SliderRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\Filesystem\Path;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user' => $user,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('article_new', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatar_articleFile */
            $avatar_articleFile = $form->get('avatar_article')->getData();
            $foto1File = $form->get('foto1')->getData();
            $foto2File = $form->get('foto2')->getData();
            
            $nameDirectiry = 'avatar_directory';
            $setImageFile = 'setAvatarArticle';
            $this->uploadsImageFile($slugger,$avatar_articleFile,$nameDirectiry,$imageController,$article,$setImageFile);
                
            $nameDirectiry = 'galery_directory';
            $setImageFile = 'setFoto1';
            $this->uploadsImageFile($slugger,$foto1File,$nameDirectiry,$imageController,$article,$setImageFile);    
            
            $nameDirectiry = 'galery_directory';
            $setImageFile = 'setFoto2';
            $this->uploadsImageFile($slugger,$foto2File,$nameDirectiry,$imageController,$article,$setImageFile); 

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новую новостную статью!'); 
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
            
        }
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }


    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article,Request $request,ManagerRegistry $doctrine,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {   
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('article_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('article/show.html.twig', [
            'article' => $article,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('article_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $foto1File = $form->get('foto1')->getData();
        $foto2File = $form->get('foto2')->getData();
        $avatar_articleFile = $form->get('avatar_article')->getData();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $getImageFile = 'getAvatarArticle';
            $setImageFile = 'setAvatarArticle';
            $nameDirectiry = 'avatar_directory';
            $this->deleteFiles($avatar_articleFile,$article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$avatar_articleFile,$nameDirectiry,$imageController,$article,$setImageFile);
            
            $getImageFile = 'getFoto1';
            $setImageFile = 'setFoto1';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($foto1File,$article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$foto1File,$nameDirectiry,$imageController,$article,$setImageFile);
                
            $getImageFile = 'getFoto2';
            $setImageFile = 'setFoto2';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($foto2File,$article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$foto2File,$nameDirectiry,$imageController,$article,$setImageFile);    
               
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно обновили новостную статью!');   
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article,ManagerRegistry $doctrine, EntityManagerInterface $entityManager,ImageController $imageController, int $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {

            $sarticle_count = $doctrine->getRepository(Article::class)->countFindAllArticle();
            if($sarticle_count <= 2){
                $this->addFlash(
                    'success',
                    'Вы не можете удалить статью, должно остаться не менее 2-х видов новостных статей!'); 
                return $this->redirectToRoute('article_show',['id' => $id], Response::HTTP_SEE_OTHER);
            }

            $getImageFile = 'getAvatarArticle';
            $setImageFile = 'setAvatarArticle';
            $nameDirectiry = 'avatar_directory';
            $this -> deleteImgFiles($article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);

            $getImageFile = 'getFoto1';
            $setImageFile = 'setFoto1';
            $nameDirectiry = 'galery_directory';
            $this -> deleteImgFiles($article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);

            $getImageFile = 'getFoto2';
            $setImageFile = 'setFoto2';
            $nameDirectiry = 'galery_directory';
            $this -> deleteImgFiles($article,$getImageFile,$setImageFile,$nameDirectiry,$imageController);

            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили новостную статью!'); 
        }
        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
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
