<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\FileUploader;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\SliderRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\Filesystem\Path;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
    public function index(ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        
        return $this->renderForm('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user' => $user,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatar_articleFile */
            $avatar_articleFile = $form->get('avatar_article')->getData();
            $foto1File = $form->get('foto1')->getData();
            $foto2File = $form->get('foto2')->getData();
            
            if ($avatar_articleFile) {
                $newFilename = $this->uploadNewAvatarName($slugger, $avatar_articleFile);
                $article->setAvatarArticle($newFilename);
            }
            if ($foto1File) {
                $newFilename1 = $this->uploadNewFileName($slugger, $foto1File);
                $article->setFoto1($newFilename1);
            }
            if ($foto2File) {
                $newFilename2 = $this->uploadNewFileName($slugger, $foto2File);
                $article->setFoto2($newFilename2);
            }
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
    public function show(Article $article,Request $request,MailerInterface $mailer): Response
    {   
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        return $this->renderForm('article/show.html.twig', [
            'article' => $article,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $foto1File = $form->get('foto1')->getData();
        $foto2File = $form->get('foto2')->getData();
        $avatar_articleFile = $form->get('avatar_article')->getData();
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($avatar_articleFile) {
                $this -> deleteAvatarFile($article);
                $newFilename = $this->uploadNewAvatarName($slugger, $avatar_articleFile);
                $article->setAvatarArticle($newFilename);
            }
            if ($foto1File) {
                $this -> deleteFoto1File($article);
                $newFilename1 = $this->uploadNewFileName($slugger, $foto1File);
                $article->setFoto1($newFilename1);
            }
            if ($foto2File) {
                $this -> deleteFoto2File($article);
                $newFilename2 = $this->uploadNewFileName($slugger, $foto2File);
                $article->setFoto2($newFilename2);
            }
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
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this -> deleteAvatarFile($article);
            $this -> deleteFoto1File($article);
            $this -> deleteFoto2File($article);
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили новостную статью!'); 
        }
        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
    private function uploadNewFileName($slugger, $imageFile)
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
    private function uploadNewAvatarName($slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);       
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();        
        try {
            $imageFile->move(
                $this->getParameter('avatar_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }
    private function deleteAvatarFile($article){
        $filesystem = new Filesystem();   
        if($article->getAvatarArticle() != null){
            $article->setAvatarArticle(
                $path = new File($this->getParameter('avatar_directory').'/'.$article->getAvatarArticle())
            );
            $filesystem->remove(['symlink', $path, $article->getAvatarArticle()]);
        }       
    }
    private function deleteFoto1File($article){
        $filesystem = new Filesystem();   
        if($article->getFoto1() != null){
            $article->setFoto1(
             $path1 = new File($this->getParameter('galery_directory').'/'.$article->getFoto1())
            ); 
            $filesystem->remove(['symlink', $path1, $article->getFoto1()]);
        }      
    }
    private function deleteFoto2File($article){
        $filesystem = new Filesystem();   
        if($article->getFoto2() != null){
            $article->setFoto2(
                $path2 = new File($this->getParameter('galery_directory').'/'.$article->getFoto2())
            );
            $filesystem->remove(['symlink', $path2, $article->getFoto2()]);
        }
    }
}
