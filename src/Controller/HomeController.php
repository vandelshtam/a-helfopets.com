<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Product;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Entity\FastConsultation;
use App\Form\HomeControllerType;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
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
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SliderRepository $sliderRepository,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $consultation = new Consultation();
        
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        //$value = 
        $article = $doctrine->getRepository(Article::class)->findByExampleField();
        //dd($article[0]->getTitle());
        //$form = $this->createForm(TaskType::class, $consult);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($consultation->getMessage());
            $entityManager->persist($consultation);
            $entityManager->flush();
            $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text($consultation->getMessage())
            ->html('<h1>Здравствуйте я '.$consultation->getName().'!</h1>
            <p>
                Пожалуйста тветтьте мне на почту : '.$consultation->getEmail().' 
            </p>
            <>или по телефону:  '.$consultation->getPhone().' 
            </p>
            </p>
            <>тема вопроса:  '.$consultation->getPhone().' 
            </p>
            </p>
            <p> Мой вопрос:  '.$consultation->getMessage().' 
            </p>
            <p><code>{{ email.to[0].address }}</code></p>
            <p>
                <a href="#">Click here to activate your account</a>    
            </p>');
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }       
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            //dd($consultation->getMessage());
            $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text($fast_consultation->getName())
            ->html('<h2>Здравствуйте я '.$fast_consultation->getName().'!</h2>
            <p>
                Проконсультируйте меня пожалуйста позвонив по номеру  '.$fast_consultation->getPhone().'
                <a href="#">Click here to activate your account</a>    
            </p>')
            ;
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }       
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
            'article' => $article,
        ]);
        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        //     'title' => 'PlumbInstall',
        //     'user'  => $user
        // ]);
    }
    
}
