<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit : '
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix du produit : ',
                'label_attr' => ['class' => ''],
                'currency' => '', //ce champ est délibérément vide car le symbol € n'est pas pris en compte par le theme
                'scale' => 2,
                'required' => true,
                'divisor' => 100,
                'attr' => ['class' => '.font-12',
                    'placeholder' => '€'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du produit : '
            ])
            ->add('datesortie', DateType::class, [
                'label' => 'Date de sortie du produit : ',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'datepicker',
                    'placeholder' => 'jj/mm/aaaa'],
            ])
            //->add('produits_commandes')
            /*->add('promotions', EntityType::class, [
                'class' => Promotion::class,
                'choice_label' => 'nom',
                'label' => 'Promotion : ',
                'placeholder' => 'Choisir une promotions',
                'required' => false,

            ])*/

            ->add('images_files', CollectionType::class, [
                'label' => 'Images du produit',
                'mapped' => false,
                'required' => false,
                'entry_type' => FileType::class,
                'allow_add' => true,
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie : ',
                'placeholder' => 'Choisir une catégorie',
                'required' => true,

            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
