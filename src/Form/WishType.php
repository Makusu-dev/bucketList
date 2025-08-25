<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('categories',EntityType::class,[
                'class'=>Category::class,
                'multiple'=>true,
                'expanded'=>true,
                'choice_label'=> 'name',
                'query_builder'=> function(CategoryRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('author')
            ->add('isPublished', Type\CheckboxType::class, ['label' => 'Publish?', 'required' => false])
            ->add('Submit',Type\SubmitType::class, ['label' => 'Publish your wish'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
