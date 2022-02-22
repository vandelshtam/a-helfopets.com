<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\UserRepository;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageAdminController extends AbstractController
{
    #[Route('/page/admin', name: 'page_admin')]
    public function index(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('page_admin', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser();
        //dd($user->getRoles());
        return $this->renderForm('page_admin/index.html.twig', [
            'controller_name' => 'PageAdminController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'user' => $user,
            'title' => 'Helfopets page admin',
        ]);
    }
    #[Route('/page/admin/{id}/', name: 'page_admin_user')]
    public function page_user(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getId() != $id){
             $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        }

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('page_admin', [], Response::HTTP_SEE_OTHER);
        }
        $user = $userRepository->find($id);
        
        return $this->renderForm('page_admin/index.html.twig', [
            'controller_name' => 'PageAdminController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'user' => $user,
            'title' => 'Helfopets page admin show',
        ]);
    }
}
