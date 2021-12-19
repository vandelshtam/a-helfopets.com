<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\FastConsultation;
use App\Service\FileUploader;
use App\Form\FastConsultationType;
use App\Repository\SliderRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SliderRepository $sliderRepository): Response
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
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer,SliderRepository $sliderRepository,SluggerInterface $slugger): Response
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
            //dd($avatar_articleFile);
            
            if ($avatar_articleFile) {
                $originalFilename = pathinfo($avatar_articleFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatar_articleFile->guessExtension();
                //$avatar_article = $fileUploader->upload($avatar_articleFile);
                

                // Move the file to the directory where brochures are stored
                try {
                    $avatar_articleFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setAvatarArticle($newFilename);
                //$article->setAvatarArticle($avatar_article);
            }
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $article->setAvatarArticle(
            new File($this->getParameter('img_directory').'/'.$article->getAvatarArticle())
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
