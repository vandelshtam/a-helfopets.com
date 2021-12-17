<?php

namespace App\Controller;

use App\Entity\Consultation;

use App\Form\ConsultationType;
use App\Form\HomeControllerType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $consultation = new Consultation();
        
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

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
            ->html('<h1>Welcome {{ '.$consultation->getEmail().' }}!</h1>

            <p>
                You signed up as {{ '.$consultation->getMessage().' }} the following email:
            </p>
            <p><code>{{ email.to[0].address }}</code></p>
            
            <p>
                <a href="#">Click here to activate your account</a>
                
            </p>')
            // path of the Twig template to render
            //->htmlTemplate('emails/signup.html.twig')
            //->textTemplate('emails/signup.txt.twig')
            // pass variables (name => value) to the template
            //->context([
            //    'expiration_date' => new \DateTime('+7 days'),
            //    'username' => 'foo',
            //])
            ;
                        try {
                            $mailer->send($email);
                        } catch (TransportExceptionInterface $e) {
                            // some error prevented the email sending; display an
                            // error message or try to resend the message
                        }
                    
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
//dd($consultation->getMessage());
        
                        return $this->renderForm('home/index.html.twig', [
                            'consultation' => $consultation,
                            'form' => $form,
                            'title' => 'PlumbInstall',
                            'user'  => $user,
                            'controller_name' => 'HomeController',
                        ]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'title' => 'PlumbInstall',
            'user'  => $user
        ]);
    }
    
}
