<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserActionType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\UserRepository;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
        }
        $users=$userRepository->findAll();
        $userses=[];
        foreach($users as $elem){
            $userses[]=$elem;
        }

        return $this->renderForm('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $userRepository->findAll(),
            'userses' => $userses,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }
    #[Route('/{id}/edit', name: 'users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getId() != $id){
             $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        }
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('users_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UserActionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('avatar')->getData();
            $getImageFile = 'getAvatar';
            $setImageFile = 'setAvatar';
            $nameDirectiry = 'avatar_directory';
            $this->deleteFiles($imageFile,$user,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$user,$setImageFile);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили данные пользователя'); 
            return $this->redirectToRoute('users', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/delete/{id}', name: 'users_delete', methods: ['GET','POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,ImageController $imageController, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getId() != $id){
             $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        }
        dd('привет');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $getImageFile = 'getAvatar';
            $setImageFile = 'setAvatar';
            $nameDirectiry = 'avatar_directory';
            $this -> deleteImgFiles($user,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash(
            'success',
            'Вы успешно информацию из  блока номер 1 на странице "О нас"'); 
        }
        
        return $this->redirectToRoute('our_mission_index', [], Response::HTTP_SEE_OTHER);
    }

    private function uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$article,$setImageFile){
        if ($imageFile) {
            $newFilename = $imageController->uploadNewFileName($slugger,$imageFile,$nameDirectiry);
            $article->$setImageFile($newFilename);
        }
    }
    private function deleteFiles($imageFile,$nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
        if ($imageFile){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
        }
    }
    private function deleteImgFiles($nameObject,$getImageFile,$setImageFile,$nameDirectiry,$imageController){
            $imageController->deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry);
    } 
}
