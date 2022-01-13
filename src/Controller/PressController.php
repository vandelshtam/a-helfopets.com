<?php

namespace App\Controller;

use App\Entity\Press;
use App\Form\PressType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\PressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/press')]
class PressController extends AbstractController
{
    #[Route('/', name: 'press_index', methods: ['GET'])]
    public function index(PressRepository $pressRepository,Request $request): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->render('press/index.html.twig', [
            'presses' => $pressRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'press_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $press = new Press();
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $press->setImg($newFilename);
            }
            $entityManager->persist($press);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новый слайдер в блоке "пресса о нас" на странице "О нас"'); 
            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/new.html.twig', [
            'press' => $press,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'press_show', methods: ['GET'])]
    public function show(Press $press,Request $request): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->render('press/show.html.twig', [
            'press' => $press,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'press_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Press $press, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        
        $form = $this->createForm(PressType::class, $press);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                
                try {
                    $imageFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "An error occurred while creating your directory at ";
                }
                $press->setImg($newFilename);
            }

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно отредактировали слайдер в  блоке "пресса о нас" на странице "О нас"'); 
            return $this->redirectToRoute('press_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('press/edit.html.twig', [
            'press' => $press,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'press_delete', methods: ['POST'])]
    public function delete(Request $request, Press $press, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$press->getId(), $request->request->get('_token'))) {
            $entityManager->remove($press);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно удалили информацию из  блока "пресса о нас" на странице "О нас"'); 
        }

        return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
    }
    private function uploadNewFileName(SluggerInterface $slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);       
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();        
        try {
            $imageFile->move(
                $this->getParameter('galery_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }    
}
