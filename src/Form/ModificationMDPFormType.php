<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class ModificationMDPFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('passwordActuel', PasswordType::class, [
                'label' => "Mot de passe actuel",
                'invalid_message' => "Le mot de passe entrer n'est pas valide",
                'constraints' => [new Assert\Length(min: 4, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères")],
                'required' => true,
            ])
            ->add('passwordModif', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe doivent être identiques",
                'constraints' => [new Assert\Length(min: 4, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères")],
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => "Nouveau Mot de passe"],
                'second_options' => ['label' => "Confirmation du Mot de passe"]
            ])
            ->add('modifier', SubmitType::class, [
                'label' => "Modifier votre Mot de passe",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /*
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
        */
    }
}
