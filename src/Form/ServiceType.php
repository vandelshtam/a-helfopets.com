<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('name',TextType::class)
            ->add('discription')
            ->add('avatar', FileType::class, [
                'label' => 'avatar (JPEG file)',
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
            ->add('image', FileType::class, [
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
            ->add('description2',TextareaType::class)
            ->add('description3',TextareaType::class)
            ->add('document')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
