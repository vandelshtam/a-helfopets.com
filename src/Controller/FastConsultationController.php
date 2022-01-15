<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class FastConsultationController extends AbstractController
{
    #[Route('/fast/consultation', name: 'fast_consultation')]
    public function fastSendMeil(Request $request,MailerInterface $mailer,$fast_consultation,MailerController $mailerController,$entityManager,$textSendMail)
    {
        $entityManager->persist($fast_consultation);
        $entityManager->flush();
        $mailerController->sendFastConsultationEmail($mailer,$fast_consultation,$textSendMail);
    }
}
