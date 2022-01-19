<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Название услуги не более 70 символов',
                'required' => true,  
            ])
            ->add('discription', TextareaType::class,[
                'label' => 'Краткое описание услуги не более 250 символов',
                'required' => true,  
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Пожалуйста выберите файл с расширением сооответствующим изображению (jpg, jpeg  и т п) Поле обязательное для заполнения',
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
            ->add('image', FileType::class, [
                'label' => 'Пожалуйста выберите файл с расширением сооответствующим изображению (jpg, jpeg  и т п) Поле обязательное для заполнения',
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
            ->add('description2',TextareaType::class, [
                'label' => 'Основное описание услуги не более 1000 символов',
                'required' => true,  
            ])
            ->add('description3',TextareaType::class, [
                'label' => 'Дополнительное описание услуги не более 1000 символов',
                'required' => true,  
            ])
            ->add('document', FileType::class, [
                'label' => 'Пожалуйста выберите файл с расширением pdf',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение pdf',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
