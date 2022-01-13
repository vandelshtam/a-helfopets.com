<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Category;
use App\Form\ServiceType;
use Doctrine\ORM\Mapping\Id;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository,Request $request): Response
    {

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/new', name: 'service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarleFile */
            $avatarFile = $form->get('avatar')->getData();
            $imageFile = $form->get('image')->getData();
            $documentFile = $form->get('document')->getData();
            if ($avatarFile) {
                $newFilename = $this->uploadNewAvatarName($slugger, $avatarFile);
                $service->setAvatar($newFilename);
            }
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $category->setFoto($newFilename);
            }
            if ($documentFile) {
                $newDocFilename = $this->uploadNewDocumentName($slugger, $documentFile);
                $service->setDocument($newDocFilename);
            }
            
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
    public function show(Service $service,Request $request,ManagerRegistry $doctrine, int $id,SluggerInterface $slugger): Response
    {
        $service = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($id);
        //dd($service);
        if($service->getCategory() != null){
            $categoryFoto = $service->getCategory();
        }
        else{
            $categoryFoto = null;
        }
        $category = new Category;
        $category->setFoto('create');
        $form = $this->createFormBuilder($category)
        ->add('foto', FileType::class, [
            'label' => 'Добавить фотографию в галерею, тип файла должен иметь расширение одного из типов изображений (jpg, jpeg, и др). Обязательное для заполнения поле. ',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new Image([
                    'maxSize' => '200000k',
                    'mimeTypes' => [
                        'image/*',    
                    ],
                    'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение соответствующее типу - "изображение"',
                ])
            ],
        ])
        ->add('comment', TextareaType::class, [
            'label' => 'Добавить комментарий или описание к фотографии, не обязательное к заполнению поле',
            'required' => false,
        ])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('foto')->getData();
            if ($imageFile) {
                $newFilename = $this->uploadNewFileName($slugger, $imageFile);
                $category->setFoto($newFilename);
            }
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

        return $this->renderForm('service/show.html.twig', [
            'service' => $service,
            'form' => $form,
            'categoryFoto' => $categoryFoto,
            'fast_consultation' => $fast_consultation,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager,SluggerInterface $slugger, int $id): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarleFile */
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $this -> deleteAvatarFile($service);
                $newFilename = $this->uploadNewAvatarName($slugger, $avatarFile);
                $service->setAvatar($newFilename);
            }
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
    public function delete(Request $request, Service $service,  EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $this -> deleteAvatarFile($service);
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
    public function deleteGalery(Category $category, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
       $categorys = $doctrine->getRepository(Category::class)->findOneByIdJoinedToService($id);
       $services = $categorys->getServices();
        foreach($services as $service){
            $service_id = $service->getId();
        }
        $serviceCategory = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($service_id);
        $category_count = 0;
        foreach($serviceCategory->getCategory() as $elem){
            $category_count += 1;
        }
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
       $entityManager->remove($category);
       $this -> deleteImageGaleryFile($category);
       $entityManager->flush();
       $this->addFlash(
        'success',
        'Вы успешно удалили фотографию и комментарий к ней!'); 
       return $this->redirectToRoute('service_show', array(
           'id' => $service_id), Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edition/galery', name: 'service_edition_galery', methods: ['POST','GET'])]
    public function editionGalery(Request $request,Category $category, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
        
        $form = $this->createFormBuilder($category)
        ->add('foto', FileType::class, [
            'label' => 'Заменить фотографию в галерею, тип файла должен иметь расширение одного из типов изображений (jpg, jpeg, и др). Обязательное для заполнения поле. ',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new Image([
                    'maxSize' => '200000k',
                    'mimeTypes' => [
                        'image/*',    
                    ],
                    'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение соответствующее типу - "изображение"',
                ])
            ],
        ])
        ->add('comment', TextareaType::class, [
            'label' => 'Редактировать комментарий или описание к фотографии, не обязательное к заполнению поле',
            'required' => false,
        ])
        ->getForm();
        $form->handleRequest($request);

         $services = $category->getServices();
         foreach($services as $service){
             $service_id = $service->getId();
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            $fotoFile = $form->get('foto')->getData();
            $comment = $form->get('comment')->getData();
            if ($fotoFile) {
                $this -> deleteImageGaleryFile($category);
                $newFilename = $this -> uploadNewFileName($slugger, $fotoFile);
                $category->setFoto($newFilename);
            }
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

        return $this->renderForm('service/edit_galery_service.html.twig', [
        'category' => $category,
        'form' => $form,
        'fast_consultation' => $fast_consultation,
        'fast_consultation_form' => $fast_consultation_form,
        ]);
    }
    private function uploadNewFileName($slugger, $imageFile)
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
    private function uploadNewAvatarName($slugger, $AvatarFile)
    {
        $originalFilename = pathinfo($AvatarFile->getClientOriginalName(), PATHINFO_FILENAME);       
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$AvatarFile->guessExtension();        
        try {
            $AvatarFile->move(
                $this->getParameter('avatar_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }
    private function uploadNewDocumentName($slugger, $documentFile)
    {
        $originalDocFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeDocFilename = $slugger->slug($originalDocFilename);
                $newDocFilename = $safeDocFilename.'-'.uniqid().'.'.$documentFile->guessExtension();
                
                try {
                    $documentFile->move(
                        $this->getParameter('files_directory'),
                        $newDocFilename
                    );
                } catch (FileException $e) {
                    echo "An error occurred while creating your directory at ";
                }
        return $newDocFilename;   
    }
    private function deleteImageGaleryFile($category){
        $filesystem = new Filesystem();
        if($category->getFoto() != null){
            $category->setFoto(
                $path = new File($this->getParameter('galery_directory').'/'.$category->getFoto())
            );
            $filesystem->remove(['symlink', $path, $category->getFoto()]);
        }
    }
    private function deleteAvatarFile($service){
        $filesystem = new Filesystem();
        if($service->getAvatar() != null){
            $service->setAvatar(
                $path_avatar = new File($this->getParameter('avatar_directory').'/'.$service->getAvatar())
            );
            $filesystem->remove(['symlink', $path_avatar, $service->getAvatar()]);
        }
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
}
