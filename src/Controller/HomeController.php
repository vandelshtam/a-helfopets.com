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
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SliderRepository $sliderRepository,ManagerRegistry $doctrine, BlogRepository $blogRepository): Response
    {
        $user = $this->getUser();
        $consultation = new Consultation();
        
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        $articles = $doctrine->getRepository(Article::class)->findByExampleField();
        $blogs = $doctrine->getRepository(Blog::class)->findByExampleField();
        $services = $doctrine->getRepository(Service::class)->findByExampleField();
        if ($form->isSubmitted() && $form->isValid()) {
            $this -> consultation($request, $entityManager,$mailer,$consultation); 
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $this -> fast_consultation($request, $entityManager,$mailer,$fast_consultation); 
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

    private function fast_consultation($request, $entityManager,$mailer,$fast_consultation){
        $entityManager->persist($fast_consultation);
               $entityManager->flush();
               $email = (new Email())
               ->from('hello@example.com')
               ->to('you@example.com')
               ->subject('Time for Symfony Mailer!')
               ->text($fast_consultation->getName())
               ->html(
               '<h1>Здравствуйте, я '.$fast_consultation->getName().' пожалуйста проконсультируйте меня!</h1>
               <p>Прошу связаться со мной по номеру телефона '.$fast_consultation->getPhone().'</p>'
               );
               try {
                   $mailer->send($email);
                   $this->addFlash(
                   'success',
                   'Вы успешно отправлили заявку, скоро мы с вами свяжемся!');     
               } catch (TransportExceptionInterface $e) {
                   $this->addFlash(
                       'success',
                       'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
               }             
    }
    
    private function consultation($request, $entityManager,$mailer,$consultation){
        $entityManager->persist($consultation);
        $entityManager->flush();
        $email = (new Email())
        ->from('hello@example.com')
        ->to('you@example.com')
        ->subject('Time for Symfony Mailer!')
        ->text($consultation->getMessage())
        ->html('<h1>Здравствуйте я '.$consultation->getName().'!</h1>
        <p>
            Пожалуйста тветтьте мне на почту : '.$consultation->getEmail().' 
        </p>
            или по телефону:  '.$consultation->getPhone().' 
        </p>
        </p>
            тема вопроса:  '.$consultation->getPhone().' 
        </p>
        <p> 
            Мой вопрос:  '.$consultation->getMessage().' 
        </p>
        <p>
            <code>{{ email.to[0].address }}</code>
        </p>
        <p>
            <a href="#">Click here to activate your account</a>    
        </p>');
        try {
            $mailer->send($email);
            $this->addFlash(
            'success',
            'Благодарим за отправленную заявку, мы обязательно вам ответим!');     
        } catch (TransportExceptionInterface $e) {
            $this->addFlash(
            'success',
            'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
        }             
    }
}
