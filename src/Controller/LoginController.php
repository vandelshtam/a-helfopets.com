<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils,Request $request,MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer,EntityManagerInterface $entityManager): Response
    {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            $fast_consultation = new FastConsultation();
            $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
            $fast_consultation_form->handleRequest($request);
            if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
                $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
                $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
                return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
            }
              
            return $this->renderForm('login/index.html.twig', [  
                  'last_username' => $lastUsername,
                  'error'         => $error,
                  'fast_consultation' => $fast_consultation,
                  'fast_consultation_form' => $fast_consultation_form,
                  ]);
            $this->addFlash(
                    'notice',
                    'Вы успешно вошли в систему!');         
    }
}
