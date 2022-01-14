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
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,$fast_consultation)
    {
        // $fast_consultation = new FastConsultation();       
        // $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        // $fast_consultation_form->handleRequest($request);

        //if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
        //dd('ghbdtn');   
        $entityManager->persist($fast_consultation);
            $entityManager->flush();
            $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text($fast_consultation->getName())
            ->html(
            '<h1>Здравствуйте, я  {{ '.$fast_consultation->getName().' }} пожалуйста проконсультируйте меня !</h1>
            <p> Прошу связаться со мной по номеру телефона {{ '.$fast_consultation->getPhone().' }} </p>'
            );
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'success',
                    'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
            }       
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
       // }

    //         return $this->renderForm('fast_consultation/index.html.twig', [
    //         'fast_consultation' => $fast_consultation,
    //         'fast_consultation_form' => $fast_consultation_form,
    //    ]);
    }

    

    // public function fast_message(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,$fast_consultation,$fast_consultation_form)
    // {
        
    //     if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
    //         $entityManager->persist($fast_consultation);
    //         $entityManager->flush();
    //         $email = (new Email())
    //         ->from('hello@example.com')
    //         ->to('you@example.com')
    //         //->cc('cc@example.com')
    //         //->bcc('bcc@example.com')
    //         //->replyTo('fabien@example.com')
    //         //->priority(Email::PRIORITY_HIGH)
    //         ->subject('Time for Symfony Mailer!')
    //         ->text($fast_consultation->getName())
    //         ->html('<h1>Welcome {{ '.$fast_consultation->getName().' }}!</h1>
    //         <p>
    //             You signed up as {{ '.$fast_consultation->getPhone().' }} the following email:
    //         </p>
    //         <p><code>{{ email.to[0].address }}</code></p>
    //         <p>
    //             <a href="#">Click here to activate your account</a>    
    //         </p>')
    //         ;
    //         try {
    //             $mailer->send($email);
    //         } catch (TransportExceptionInterface $e) {
    //             // some error prevented the email sending; display an
    //             // error message or try to resend the message
    //         }       
    //         return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    //     }
    // }

}
