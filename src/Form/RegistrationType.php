<?php

namespace App\Form;

use App\Entity\CodePostal;
use App\Entity\Commune;
use App\Entity\Localite;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => ' ',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Adresse email'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions',
                'label_attr' => [
                    'class' => 'checkbox-custom-label',
                ],
                'attr' => [
                    'class' => 'checkbox-custom'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d\'accepter les conditions d\'utilisation.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => ' ',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de noter un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('typeutilisateur', ChoiceType::class, [
                'label' => 'Quel type d\'utilisateur êtes-vous ?',
                'choices' => [
                    'Prestataire' => 'prestataire',
                    'Internaute' => 'internaute',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])


            /*->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-theme full-width'
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
