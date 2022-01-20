<?php

namespace App\Form;

use App\Entity\Achievements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AchievementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextareaType::class,[
                'label' => 'Заголовок блока  не более 250 символов',
                'required' => true,  
            ])
            ->add('img', FileType::class, [
                'label' => 'Фотография раздела (JPEG file)',
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
            ->add('paragraph', TextareaType::class,[
                'label' => 'Дополнительный праграф перед ссылками блока  не более 250 символов',
                'required' => true,  
            ])
            ->add('link', TextareaType::class,[
                'label' => 'Ссылки,  источники  не более 250 символов',
                'required' => true,  
            ])
            ->add('text', TextareaType::class,[
                'label' => 'Оновной текст блока  не более 1000 символов',
                'required' => true,  
            ])
            ->add('document1', FileType::class, [
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
            ->add('document2', FileType::class, [
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
            ->add('document3', FileType::class, [
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
            'data_class' => Achievements::class,
        ]);
    }
}
