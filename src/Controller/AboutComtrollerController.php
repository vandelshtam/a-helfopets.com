<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Entity\OurMission;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\PressRepository;
use App\Repository\OurMissionRepository;
use App\Repository\AchievementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutComtrollerController extends AbstractController
{
    #[Route('/about/comtroller', name: 'about_comtroller')]
    public function index(Request $request,OurMissionRepository $ourMissionRepository,AchievementsRepository $achievementsRepository,PressRepository $pressRepository): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $rating = new Rating();
        // $rating_form = $this->createForm(RatingType::class, $rating);
        // $rating_form -> handleRequest($request);

        // $form = $this->createFormBuilder($rating)
        //     ->add('grade', RadioType::class, ['label' => 'rating-1'])
        //     ->add('grade', RadioType::class, ['label' => 'rating-2'])
        //     ->add('grade', RadioType::class, ['label' => 'rating-3', 'mapped' => false])
        //     ->add('grade', RadioType::class)
        //     // ->add('grade', RadioType::class, ['label' => 'rating-5'])
        //     ->getForm();
        
        return $this->renderForm('about_comtroller/index.html.twig', [
            'controller_name' => 'AboutComtrollerController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'our_missions' => $ourMissionRepository->findAll(),
            'achievements' => $achievementsRepository->findAll(),
            'presses' => $pressRepository->findAll(),
            // 'rating_form' => $form,
        ]);
    }
}
