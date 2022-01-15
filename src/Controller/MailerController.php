<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'mailer')]
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
    /**
     * @Route("mailer/email")
     */
    public function sendConsultationEmail(MailerInterface $mailer,$consultation,$textSendMail)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text($consultation->getMessage())
            ->html($textSendMail);
            try {
                $mailer->send($email);
                $this->addFlash(
                'success',
                'Благодарим за отправленную заявку, мы обязательно вам ответим!');     
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                'success',
                'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
            }             
    }

    public function sendFastConsultationEmail(MailerInterface $mailer,$fast_consultation,$textSendMail)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text($fast_consultation->getName())
            ->html($textSendMail);
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

    public function textConsultationMail($consultation){
        $textConsultation = 
            '<h1>Здравствуйте я '.$consultation->getName().'!</h1>
            <p>
                Пожалуйста отправьте ответ на мою почту : '.$consultation->getEmail().' 
            </p>
                или по телефону:  '.$consultation->getPhone().' 
            </p>
            </p>
                тема вопроса:  '.$consultation->getPhone().' 
            </p>
            <p> 
                Мой вопрос:  '.$consultation->getMessage().' 
            </p>
            <p>
                <code>{{ email.to[0].address }}</code>
            </p>
            <p>
                <a href="#">Click here to activate your account</a>    
            </p>';
        return $textConsultation;
    }
    public function textFastConsultationMail($fast_consultation){
        $textFastConsultation = 
            '<h1>Здравствуйте, я '.$fast_consultation->getName().' пожалуйста проконсультируйте меня!</h1>
            <p>Прошу связаться со мной по номеру телефона '.$fast_consultation->getPhone().'</p>';
        return $textFastConsultation;
    }
}
