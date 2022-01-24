<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('isVerified')
            ->add('name',TextType::class, [
                'label' => 'Введите имя которое будет видно на сайте',
                'required' => true,  
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Ваш аватар который будет видно на сайте (JPEG file)',
                'mapped' => false,
                'required' => true,
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
            'data_class' => User::class,
        ]);
    }
}
