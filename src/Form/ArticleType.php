<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('avatar_article', FileType::class, [
                'label' => 'avatar_article (JPEG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '200000000k',
                        'mimeTypes' => [
                            'image/*',
                            
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('comment_foto')
            ->add('paragraph1', TextareaType::class)
            ->add('foto1', FileType::class, [
                'label' => 'foto 1 (JPEG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '20000000k',
                        'mimeTypes' => [
                            'image/*',
                            
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('comment_auxiliary_one')
            ->add('paragraph2')
            ->add('article',TextareaType::class)
            ->add('foto2', FileType::class, [
                'label' => 'foto 2 (JPEG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '20000000k',
                        'mimeTypes' => [
                            'image/*',
                            
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('comment_foto2')
            ->add('paragraph3')
            ->add('paragraph4')
            ->add('author')
            ->add('preview')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
