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
            ->add('email',TextType::class)
            ->add('phone',TextType::class)
            ->add('name',TextType::class)
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    '+79957771830' => '+791739390104',
                    '+791739390104' => '+791739390104',
                    '+79957771831' => "+79957771831",
                ]
            ])
            ->add('message',TextareaType::class)
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
