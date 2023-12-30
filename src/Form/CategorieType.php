<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la catégorie : ',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom de la catégorie doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de la catégorie doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la catégorie : ',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'La description de la catégorie doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La description de la catégorie doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            //->add('promotions')
            ->add('Enregistrer', SubmitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
