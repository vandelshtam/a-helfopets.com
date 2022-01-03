<?php

namespace App\Form;

use App\Entity\OurMission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OurMissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class, [
            'label' => 'Название блока не более 70 символов',
            'required' => true,  
        ])
        ->add('title', TextareaType::class,[
            'label' => 'Заголовок блока  не более 250 символов',
            'required' => true,  
        ])
        ->add('text', TextareaType::class,[
            'label' => 'Основной текст блока не более 750 символов',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OurMission::class,
        ]);
    }
}
