<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormcreatController extends AbstractController
{
    #[Route('/formcreat', name: 'formcreat')]
    public function index(): Response
    {
        return $this->render('formcreat/index.html.twig', [
            'controller_name' => 'FormcreatController',
        ]);
    }
    public function formCreatedGaleryService($nameObject){
        $form = $this->createFormBuilder($nameObject)
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
        return $form;
    }
}
