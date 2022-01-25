<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Entity\FastConsultation;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route('/slider')]
class SliderController extends AbstractController
{

    #[Route('/', name: 'slider_index', methods: ['GET','POST'])]
    public function index(SliderRepository $sliderRepository,Request $request, EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('slider_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/new', name: 'slider_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('slider_new', [], Response::HTTP_SEE_OTHER);
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
    public function show(Slider $slider,Request $request, EntityManagerInterface $entityManager,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('slider_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('slider/show.html.twig', [
            'slider' => $slider,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form
        ]);
    }

    #[Route('/{id}/edit', name: 'slider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slider $slider, EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('slider_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
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

    #[Route('/delete/{id}', name: 'slider_delete', methods: ['POST'])]
    public function delete(Request $request, Slider $slider, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
