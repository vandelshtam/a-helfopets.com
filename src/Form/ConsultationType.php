<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Consultation;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ConsultationType extends AbstractType
{
    public function ArticleFormSelected(ArticleRepository $articleRepository, int $id)
    {
        return $articles = $articleRepository->find($id);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class, [
                'label' => 'Ваш email',
                'required' => true,  
            ])
            ->add('phone',TextType::class, [
                'label' => 'Ваш номер телефона',
                'required' => true,  
            ])
            ->add('name',TextType::class, [
                'label' => 'Ваше имя',
                'required' => true,  
            ])
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Кошки' => 1,
                    'Собаки' => 2,
                    'Птицы' => 3,
                    'Другие животные' => 4,
                ]
            ])
            ->add('message',TextareaType::class, [
                'label' => 'Ваше сообщение',
                'required' => true,  
            ])
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
