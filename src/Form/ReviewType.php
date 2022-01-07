<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('answer_id')
            //->add('created_at')
            ->add('name',TextType::class, [
                'label' => 'Ваше имя не более 70 символов',
                'required' => true,  
            ])
            ->add('text',TextType::class, [
                'label' => 'Текст сообщения не более 250 символов',
                'required' => true,  
            ])
                ->add('foto', FileType::class, [
                    'label' => 'Фотография отзыва (JPEG, JPG, WEBP file)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '20000000k',
                            'mimeTypes' => [
                                'image/*',    
                            ],
                            'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение соответствующее типу - "изображение"',
                        ])
                    ],
                ])
                ->add('foto2', FileType::class, [
                    'label' => 'Фотография отзыва (JPEG, JPG, WEBP file)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '20000000k',
                            'mimeTypes' => [
                                'image/*',    
                            ],
                            'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение соответствующее типу - "изображение"',
                        ])
                    ],
                ])
                ->add('foto3', FileType::class, [
                    'label' => 'Фотография отзыва (JPEG, JPG, WEBP file)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '20000000k',
                            'mimeTypes' => [
                                'image/*',    
                            ],
                            'mimeTypesMessage' => 'Пожалуйста выберите файл имеющий расширение соответствующее типу - "изображение"',
                        ])
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
