<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Slider;

use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Entity\FastConsultation;
use App\Form\HomeControllerType;
use Symfony\Component\Mime\Email;
use App\Form\FastConsultationType;
use App\Repository\BlogRepository;
use App\Controller\MailerController;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\ConsultationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SliderRepository $sliderRepository,ManagerRegistry $doctrine, BlogRepository $blogRepository,FastConsultationController $fast_consultation_meil, ConsultationController $consultation_mail, MailerController $mailerController): Response
    {
        $user = $this->getUser();
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);
        $articles = $doctrine->getRepository(Article::class)->findByExampleField();
        $blogs = $doctrine->getRepository(Blog::class)->findByExampleField();
        $services = $doctrine->getRepository(Service::class)->findByExampleField();
        if ($form->isSubmitted() && $form->isValid()) {
            $textSendMail = $mailerController->textConsultationMail($consultation);
            $consultation_mail -> newConsultationSend($request,$mailer,$consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        //эксперимент с погодой
        $open = new OpenWeatherController();
        $openWeather = $open->openWeather();
        $kelvin = $openWeather['main']['temp'];
        $tempCelsius = $kelvin - 273.15;
        date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу
        $time = time(); // Вот это значение отправляем в базу
        $offset = 3; // Допустим, у пользователя смещение относительно Гринвича составляет +3 часа
        $time += 3 * 3600; // Добавляем 3 часа к времени по Гринвичу
        
        $today = date("F j, Y, g:i a", $time);
        return $this->renderForm('home/index.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'Helfopets home',
            'user'  => $user,
            'sliders' => $sliderRepository->findAll(),
            'controller_name' => 'HomeController',
            'articles' => $articles,
            'blogs' => $blogs,
            'services' => $services,
            'openWeather' => $openWeather,
            'data' => $today,
            'tempCelsius' => $tempCelsius, 
        ]);
    }
}
