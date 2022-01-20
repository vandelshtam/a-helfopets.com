<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextareaType::class, [
                'label' => 'Название поста не более 100 символов',
                'required' => true,  
            ])
            ->add('preview',TextareaType::class, [
                'label' => 'Превью поста не более 250 символов',
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
            ->add('text',TextareaType::class, [
                'label' => 'Текст поста не более 750 символов',
                'required' => true,  
            ])
            ->add('text2',TextareaType::class, [
                'label' => 'Текст поста абзац 2 не более 750 символов',
                'required' => true,  
            ])
            ->add('author',TextType::class, [
                'label' => 'Автор поста не более 250 символов',
                'required' => true,  
            ])
            ->add('linltitle',TextType::class, [
                'label' => 'Название ссылки источника поста не более 250 символов',
                'required' => true,  
            ])
            ->add('link',TextareaType::class, [
                'label' => 'Ссылка на источник  поста не более 250 символов',
                'required' => true,  
            ])
            ->add('titleslider',TextareaType::class, [
                'label' => 'Заголовок слайдера не более 100 символов',
                'required' => false,  
            ])
            ->add('foto2', FileType::class, [
                'label' => 'Фотография для слайдера поста. Пожалуйста выберите файл с расширением сооответствующим изображению (jpg, jpeg, webp  и т п) Поле обязательное для заполнения',
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
            ->add('descriptionslider',TextareaType::class, [
                'label' => 'Описание к слайдеру  поста не более 100 символов',
                'required' => true,  
            ])
            ->add('linkslider',TextareaType::class, [
                'label' => 'Автор, ссылки, источники инофрмации слайдера поста не более 100 символов',
                'required' => true,  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
