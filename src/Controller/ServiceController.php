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
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
                
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "An error occurred while creating your directory at ";
                }
                $service->setAvatar($newFilename);
            }
            if ($imageFile) {
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
                
                $category->setFoto($newFilename);
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
        
        if($service->getCategory() != null){
            $categoryFoto = $service->getCategory();
        }
        $category = new Category;
        $category->setFoto('create');
        $form = $this->createFormBuilder($category)
        ->add('foto', FileType::class, [
            //'action' => $this->generateUrl('service_show/{id}'),
            'label' => 'image (JPEG file)',
            'mapped' => false,
            'required' => false,
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
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    
            $imageFile = $form->get('foto')->getData();
;
            if ($imageFile) {
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
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarleFile */
            $avatarFile = $form->get('avatar')->getData();

            $filesystem = new Filesystem();
            if ($avatarFile) {
                if($service->getAvatar() != null){
                    $service->setAvatar(
                        $path_avatar = new File($this->getParameter('avatar_directory').'/'.$service->getAvatar())
                    );
                    $filesystem->remove(['symlink', $path_avatar, $service->getAvatar()]);
                }
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "An error occurred while creating your directory at ";
                }
                $service->setAvatar($newFilename);
            }
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Вы успешно обновили информацию об услуге!'); 
                return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
        }
            return $this->renderForm('service/edit.html.twig', [
                'service' => $service,
                'form' => $form,
                'fast_consultation' => $fast_consultation,
                'fast_consultation_form' => $fast_consultation_form,
            ]);
        
}
#[Route('/{id}/edit/galery', name: 'service_edit_galery', methods: ['GET', 'POST'])]
    public function editGalery(Request $request, Service $service, EntityManagerInterface $entityManager,SluggerInterface $slugger,ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        
       
        $category->setCategory('create');
        

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        $service = new Service();

        $fast_consultation = new FastConsultation();
        $fast_consultation_form = $this->createForm(FastConsultationType::class, $fast_consultation);
        $fast_consultation_form->handleRequest($request);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageleFile */
            $imageFile = $form->get('image')->getData();
            $filesystem = new Filesystem();
            if ($imageFile) {
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
                $category->setFoto($newFilename);
            }
            $service->addCategory($category);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно обновили информацию об услуге!'); 
            return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
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
            $filesystem = new Filesystem();
            if($service->getAvatar() != null){
                $service->setAvatar(
                    $path_avatar = new File($this->getParameter('avatar_directory').'/'.$service->getAvatar())
                );
                $filesystem->remove(['symlink', $path_avatar, $service->getAvatar()]);
            }
            $service = $doctrine->getRepository(Service::class)->findOneByIdJoinedToCategory($id);
            $categoryFotos = $service->getCategory();
            foreach ($categoryFotos as $categoryFoto){
                if($categoryFoto->getFoto() != null){
                    $categoryFoto->setFoto(
                        $path_galery = new File($this->getParameter('galery_directory').'/'.$categoryFoto->getFoto())
                    );
                    $filesystem->remove(['symlink', $path_galery, $categoryFoto->getFoto()]);
                }
                $entityManager->remove($categoryFoto);
            }
            $entityManager->remove($service);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            'Вы успешно удалили услугу!'); 
        return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete/galery', name: 'service_delete_galery', methods: ['POST','GET'])]
    public function deleteGalery(Request $request,Category $category, EntityManagerInterface $entityManager, int $id, ManagerRegistry $doctrine): Response
    {
        $category = $doctrine->getRepository(Category::class)->findOneByIdJoinedToService($id);
        $services = $category->getServices();
        foreach($services as $service){
            $service_id = $service->getId();
        }
       $category = $doctrine->getRepository(Category::class)->find($id);
       $services = $category->getServices();
        
       $entityManager->remove($category);
       $filesystem = new Filesystem();
       $category->setFoto(
           $path_galery = new File($this->getParameter('galery_directory').'/'.$category->getFoto())
       );
       $filesystem->remove(['symlink', $path_galery, $category->getFoto()]);
       $entityManager->flush();
       $this->addFlash(
        'success',
        'Вы успешно удалили фотографию!'); 
       return $this->redirectToRoute('service_show', array(
           'id' => $service_id), Response::HTTP_SEE_OTHER);
    }
}
