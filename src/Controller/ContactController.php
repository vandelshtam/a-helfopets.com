<?php

namespace App\Controller;

use index;
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

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request,EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $this -> fast_consultation($request, $entityManager,$mailer,$fast_consultation); 
            return $this->redirectToRoute('contact', [], Response::HTTP_SEE_OTHER);
            }
        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'fast_consultation_form' => $fast_consultation_form,
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
}
