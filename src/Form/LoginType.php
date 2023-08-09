<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Votre adresse email',
                    'class' => 'edd-login-username'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]
            ])
            ->add('Connexion', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-theme full-width'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
