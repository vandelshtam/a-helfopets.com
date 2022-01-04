<?php

namespace App\Controller;

use App\Entity\OurMission;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\PressRepository;
use App\Repository\OurMissionRepository;
use App\Repository\AchievementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutComtrollerController extends AbstractController
{
    #[Route('/about/comtroller', name: 'about_comtroller')]
    public function index(Request $request,OurMissionRepository $ourMissionRepository,AchievementsRepository $achievementsRepository,PressRepository $pressRepository): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        
        return $this->renderForm('about_comtroller/index.html.twig', [
            'controller_name' => 'AboutComtrollerController',
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
            'our_missions' => $ourMissionRepository->findAll(),
            'achievements' => $achievementsRepository->findAll(),
            'presses' => $pressRepository->findAll(),
        ]);
    }
}
