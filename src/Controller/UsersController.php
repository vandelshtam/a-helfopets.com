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

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
        }
        $users=$userRepository->findAll();
        $userses=[];
        foreach($users as $elem){
            $userses[]=$elem;
        }
        //dd($userses[0]->getRoles()[0]);

        return $this->renderForm('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $userRepository->findAll(),
            'userses' => $userses,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }
}
