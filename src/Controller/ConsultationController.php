<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use Symfony\Component\Mime\Email;
use App\Controller\MailerController;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
    #[Route('/', name: 'consultation_new', methods: ['GET', 'POST'])]
    public function newConsultationSend(Request $request,MailerInterface $mailer,$consultation,MailerController $mailerController,$entityManager,$textSendMail)
    {
        $entityManager->persist($consultation);
        $entityManager->flush();
        $mailerController->sendConsultationEmail($mailer,$consultation,$textSendMail);
    }
}
