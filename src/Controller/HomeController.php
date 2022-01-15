<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Slider;

use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Entity\FastConsultation;
use App\Form\HomeControllerType;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use App\Repository\BlogRepository;
use App\Controller\MailerController;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\ConsultationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SliderRepository $sliderRepository,ManagerRegistry $doctrine, BlogRepository $blogRepository,FastConsultationController $fast_consultation_meil, ConsultationController $consultation_mail, MailerController $mailerController): Response
    {
        $user = $this->getUser();
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        $articles = $doctrine->getRepository(Article::class)->findByExampleField();
        $blogs = $doctrine->getRepository(Blog::class)->findByExampleField();
        $services = $doctrine->getRepository(Service::class)->findByExampleField();
        if ($form->isSubmitted() && $form->isValid()) {
            $textSendMail = $mailerController->textConsultationMail($consultation);
            $consultation_mail -> newConsultationSend($request,$mailer,$consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('home/index.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'PlumbInstall',
            'user'  => $user,
            'sliders' => $sliderRepository->findAll(),
            'controller_name' => 'HomeController',
            'articles' => $articles,
            'blogs' => $blogs,
            'services' => $services,
        ]);
    }
}
