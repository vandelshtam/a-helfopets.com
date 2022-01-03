<?php

namespace App\Controller;

use App\Entity\OurMission;
use App\Form\OurMissionType;
use App\Service\FileUploader;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\OurMissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/our/mission')]
class OurMissionController extends AbstractController
{
    #[Route('/', name: 'our_mission_index', methods: ['GET'])]
    public function index(OurMissionRepository $ourMissionRepository): Response
    {
        return $this->render('our_mission/index.html.twig', [
            'our_missions' => $ourMissionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'our_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $ourMission = new OurMission();
        $form = $this->createForm(OurMissionType::class, $ourMission);
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
                $ourMission->setImg($newFilename);
            }

            $entityManager->persist($ourMission);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно заполнили блок номер 1 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('our_mission/new.html.twig', [
            'our_mission' => $ourMission,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'our_mission_show', methods: ['GET'])]
    public function show(OurMission $ourMission): Response
    {
        return $this->render('our_mission/show.html.twig', [
            'our_mission' => $ourMission,
        ]);
    }

    #[Route('/{id}/edit', name: 'our_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OurMission $ourMission, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(OurMissionType::class, $ourMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img')->getData();
            $filesystem = new Filesystem();
            if ($imageFile) {
                if($ourMission->getImg() != null){
                    $ourMission->setImg(
                        $path = new File($this->getParameter('img_directory').'/'.$ourMission->getImg())
                    );
                    $filesystem->remove(['symlink', $path, $ourMission->getImg()]);
                }
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
                $ourMission->setImg($newFilename);
            }
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно отредактировали блок номер 1 на странице "О нас"'); 
            return $this->redirectToRoute('about_comtroller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('our_mission/edit.html.twig', [
            'our_mission' => $ourMission,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'our_mission_delete', methods: ['POST'])]
    public function delete(Request $request, OurMission $ourMission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ourMission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ourMission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('our_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
