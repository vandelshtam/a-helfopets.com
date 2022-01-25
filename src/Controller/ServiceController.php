<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Category;
use App\Form\ServiceType;
use Doctrine\ORM\Mapping\Id;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\ImageController;
use App\Controller\MailerController;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository,Request $request, EntityManagerInterface $entityManager,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger, ManagerRegistry $doctrine,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();
        
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('service_new', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarleFile */
            $avatarFile = $form->get('avatar')->getData();
            $nameDirectiry = 'avatar_directory';
            $setImageFile = 'setAvatar';
            $this->uploadsImageFile($slugger,$avatarFile,$nameDirectiry,$imageController,$service,$setImageFile);

            $imageFile = $form->get('image')->getData();
            $nameDirectiry = 'galery_directory';
            $setImageFile = 'setFoto';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$category,$setImageFile);

            $documentFile = $form->get('document')->getData();
            $nameDirectiry = 'files_directory';
            $setImageFile = 'setDocument';
            $this->uploadsImageFile($slugger,$documentFile,$nameDirectiry,$imageController,$service,$setImageFile);
            
            $service->addCategory($category);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно создали новую услугу!'); 
            return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

   #[Route('/{id}', name: 'service_show', methods: ['GET', 'POST'])]
    public function show(Service $service,Request $request,ManagerRegistry $doctrine, int $id,SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer,FormcreatController $formcreatController): Response
    {
        $service = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($id);
        if($service->getCategory() != null){
            $categoryFoto = $service->getCategory();
        }
        else{
            $categoryFoto = null;
        }
        $category = new Category;
        $category->setFoto('create');
        $form = $formcreatController->formCreatedGaleryService($category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            $nameDirectiry = 'galery_directory';
            $setImageFile = 'setFoto';
            $this->uploadsImageFile($slugger,$imageFile,$nameDirectiry,$imageController,$category,$setImageFile);
            $service->addCategory($category);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно добавили новую фотографию!'); 
            return $this->redirectToRoute('service_show', array(
                'id' => $service->getId()), Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('service_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/show.html.twig', [
            'service' => $service,
            'form' => $form,
            'categoryFoto' => $categoryFoto,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service,ManagerRegistry $doctrine, EntityManagerInterface $entityManager,SluggerInterface $slugger, int $id,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('service_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        $category = $this->serviceCategoryId($doctrine,$id);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarleFile */
            $avatarFile = $form->get('avatar')->getData();
            $getImageFile = 'getAvatar';
            $setImageFile = 'setAvatar';
            $nameDirectiry = 'avatar_directory';
            $this->deleteFiles($avatarFile,$service,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$avatarFile,$nameDirectiry,$imageController,$service,$setImageFile);

            $fotoFile = $form->get('image')->getData();
            $getFotoFile = 'getFoto';
            $setFotoFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($fotoFile,$category,$getFotoFile,$setFotoFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$fotoFile,$nameDirectiry,$imageController,$category,$setFotoFile);

            $documentFile = $form->get('document')->getData();
            $getFileFile = 'getDocument';
            $setFileFile = 'setDocument';
            $nameDirectiry = 'files_directory';
            $this->deleteFiles($documentFile,$service,$getFileFile,$setFileFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$documentFile,$nameDirectiry,$imageController,$service,$setFileFile);

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно обновили информацию об услуге!'); 
            return $this->redirectToRoute('service_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
        
}

    #[Route('/{id}/delete', name: 'service_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Service $service,  EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine,ImageController $imageController): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $service_count = $doctrine->getRepository(Service::class)->countFindAllService();
            if($service_count <= 3){
                $this->addFlash(
                    'success',
                    'Вы не можете удалить услугу, должно остаться не менее 3-х видов услуг!'); 
                return $this->redirectToRoute('service_show',['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $getImageFile = 'getAvatar';
            $setImageFile = 'setAvatar';
            $nameDirectiry = 'avatar_directory';
            $this -> deleteImgFiles($service,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $getFileFile = 'getDocument';
            $setFileFile = 'setDocument';
            $nameDirectiry = 'files_directory';
            $this -> deleteImgFiles($service,$getFileFile,$setFileFile,$nameDirectiry,$imageController);
            $service = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($id);
            $categoryFotos = $service->getCategory();
            $this -> deleteAllGaleryFile($categoryFotos,$entityManager);
            $entityManager->remove($service);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            'Вы успешно удалили услугу!'); 
        return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete/galery', name: 'service_delete_galery', methods: ['POST','GET'])]
    public function deleteGalery(Category $category, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine,ImageController $imageController): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $categorys = $doctrine->getRepository(Category::class)->findOneByIdJoinedToService($id);
        $services = $categorys->getServices();
        foreach($services as $service){
            $service_id = $service->getId();
        }
        $serviceCategory = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($service_id);
        $category_count = $this->serviceCategoryCount($serviceCategory);
        if($category_count == 1){
            $this->addFlash(
                'success',
                'Вы не можете удалить все фотографии и коментарии в галерее услуги, должна остаться одна фотография и комментарий!'); 
            return $this->redirectToRoute('service_show', array(
                'id' => $service_id), Response::HTTP_SEE_OTHER);
        }
       $services = $category->getServices();
       
       foreach($services as $service){
            $service_id = $service->getId();
        }   
       $getImageFile = 'getFoto';
       $setImageFile = 'setFoto';
       $nameDirectiry = 'galery_directory';
       $this -> deleteImgFiles($category,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
       $entityManager->remove($category);
       $entityManager->flush();
       $this->addFlash(
        'success',
        'Вы успешно удалили фотографию и комментарий к ней!'); 
       return $this->redirectToRoute('service_show', array(
           'id' => $service_id), Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit/galery', name: 'service_edition_galery', methods: ['POST','GET'])]
    public function editionGalery(Request $request,Category $category, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine, SluggerInterface $slugger,ImageController $imageController, MailerController $mailerController,FastConsultationController $fast_consultation_meil,MailerInterface $mailer,FormcreatController $formcreatController): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $category = $doctrine->getRepository(Category::class)->find($id);
        $form = $formcreatController->formCreatedGaleryService($category);
        $form->handleRequest($request);

        $services = $category->getServices();
        foreach($services as $service){
             $service_id = $service->getId();
        }       
        if ($form->isSubmitted() && $form->isValid()) {
            $fotoFile = $form->get('foto')->getData();
            $getImageFile = 'getFoto';
            $setImageFile = 'setFoto';
            $nameDirectiry = 'galery_directory';
            $this->deleteFiles($fotoFile,$category,$getImageFile,$setImageFile,$nameDirectiry,$imageController);
            $this->uploadsImageFile($slugger,$fotoFile,$nameDirectiry,$imageController,$category,$setImageFile);
            $comment = $form->get('comment')->getData();
            $category->setComment($comment);
            $entityManager->flush();
            $this->addFlash(
            'success',
            'Вы успешно обновили фотографию и комментарий!');
            
            return $this->redirectToRoute('service_show', array(
                'id' => $service_id), Response::HTTP_SEE_OTHER); 
        }

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail); 
            return $this->redirectToRoute('service_edition_galery', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit_galery_service.html.twig', [
        'category' => $category,
        'form' => $form,
        'fast_consultation' => $fast_consultation,
        'fast_consultation_form' => $fast_consultation_form,
        ]);
    }
    private function deleteAllGaleryFile($categoryFotos,$entityManager){
        $filesystem = new Filesystem();
        foreach ($categoryFotos as $categoryFoto){
            if($categoryFoto->getFoto() != null){
                $categoryFoto->setFoto(
                    $path_galery = new File($this->getParameter('galery_directory').'/'.$categoryFoto->getFoto())
                );
                $filesystem->remove(['symlink', $path_galery, $categoryFoto->getFoto()]);
            }
            $entityManager->remove($categoryFoto);
        }
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
    private function serviceCategoryCount($serviceCategory){
        $category_count = 0;
        foreach($serviceCategory->getCategory() as $elem){
            $category_count += 1;
        }
        return $category_count;
    } 
    private function serviceCategoryId($doctrine,$id){
        $categoryFotoServiceId = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($id)->getCategory();
        $arreyIdCategory = [];
        foreach($categoryFotoServiceId as $elem){
            $arreyIdCategory[] = $elem->getId();
        }
        $category = $doctrine->getRepository(Category::class)->find($arreyIdCategory[0]);
        return $category;
    }
}
