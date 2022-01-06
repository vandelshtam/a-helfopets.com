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
        ->add('text',TextType::class, [
            'label' => 'отзыв не более 70 символов',
            'required' => true,  
        ])
        ->add('username',TextType::class, [
            'label' => 'имя не более 70 символов',
            'required' => true,  
        ])
            ->add('fotorev', FileType::class, [
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
            ->add('fotorev2', FileType::class, [
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
