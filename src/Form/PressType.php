<?php

namespace App\Form;

use App\Entity\Press;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PressType extends AbstractType
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
            ->add('text', TextareaType::class,[
                'label' => 'Оновной текст блока  не более 1000 символов',
                'required' => true,  
            ])
            ->add('sources', TextareaType::class,[
                'label' => 'Источники информации  не более 250 символов',
                'required' => true,  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Press::class,
        ]);
    }
}
