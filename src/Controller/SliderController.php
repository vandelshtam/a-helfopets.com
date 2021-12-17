<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Entity\FastConsultation;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route('/slider')]
class SliderController extends AbstractController
{

    #[Route('/', name: 'slider_index', methods: ['GET'])]
    public function index(SliderRepository $sliderRepository,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $this->fast_message($fast_consultation, $mailer);
        }

        return $this->renderForm('slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/new', name: 'slider_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $this->fast_message($fast_consultation, $mailer);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($slider);
            $entityManager->flush();

            return $this->redirectToRoute('slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/{id}', name: 'slider_show', methods: ['GET'])]
    public function show(Slider $slider,Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $this->fast_message($fast_consultation, $mailer);
        }
        
        return $this->renderForm('slider/show.html.twig', [
            'slider' => $slider,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/{id}/edit', name: 'slider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slider $slider, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $this->fast_message($fast_consultation, $mailer);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/{id}', name: 'slider_delete', methods: ['POST'])]
    public function delete(Request $request, Slider $slider, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('slider_index', [], Response::HTTP_SEE_OTHER);
    }

    private function fast_message($fast_consultation,$mailer)
    {
            //dd($fast_consultation);
           
            $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text($fast_consultation->getName())
            ->html('<h1>Здравствуйте, я  {{ '.$fast_consultation->getName().' }} пожалуйста проконсультируйте меня !</h1>
            <p>
                Прошу связаться со мной по номеру телефона {{ '.$fast_consultation->getPhone().' }} 
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
}
