<?php

namespace App\Form;

use App\Entity\Fotoblog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FotoblogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title',TextareaType::class, [
            'label' => 'Название поста не более 100 символов',
            'required' => true,  
        ])
        ->add('foto', FileType::class, [
            'label' => 'Главная фотография поста. Пожалуйста выберите файл с расширением сооответствующим изображению (jpg, jpeg, webp  и т п) Поле обязательное для заполнения',
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
            ->add('description',TextareaType::class, [
                'label' => 'Краткое описание темы поста сообщения не более 250 символов',
                'required' => true,  
            ])
            ->add('link',TextareaType::class, [
                'label' => 'Ссылка на источник  поста не более 250 символов',
                'required' => true,  
            ])
            ->add('blog')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fotoblog::class,
        ]);
    }
}
