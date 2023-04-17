<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('courriel', EmailType::class, [
                'required' => true,
                'label' => 'Courriel d\'utilisateur',
                'attr' => []
            ])
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => []
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'attr' => []
            ])
            ->add('adresse', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'attr' => []
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'attr' => []
            ])
            ->add('codePostal', TextType::class, [
                'required' => true,
                'label' => 'Code postal',
                'attr' => []
            ])
            ->add('province', ChoiceType::class, [
                'required' => true,
                'label' => 'Province',
                'attr' => [],
                'choices' => [
                    'Alberta' => 'AB',
                    'Colombie-Britannique' => 'BC',
                    'Île-du-Prince-Édouard' => 'PE',
                    'Manitoba' => 'MB',
                    'Nouveau-Brunswick' => 'NB',
                    'Nouvelle-Écosse' => 'NS',
                    'Ontario' => 'ON',
                    'Québec' => 'QC',
                    'Saskatchewan' => 'SK',
                    'Terre-Neuve-et-Labrador' => 'NL',
                    'Territoires du Nord-Ouest' => 'NT',
                    'Nunavut' => 'NU',
                    'Yukon' => 'YT'
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'attr' => []
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe doivent être identiques",
                'constraints' => [new Assert\Length(min: 4, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères")],
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => "Mot de passe"],
                'second_options' => ['label' => "Confirmation du mot de passe"]
            ])
            ->add('create', SubmitType::class, [
                'label' => "Créer votre compte",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);

        // Modifier le input pour qu'il soit ok pour entrer en base de données
        $builder->get('telephone')->addModelTransformer(new CallbackTransformer(
            function ($phoneFromDataBase) {
                //To View
                $newPhone = substr_replace($phoneFromDataBase, "-", 3, 0);
                return substr_replace($newPhone, "-", 7, 0);
            },
            function ($phoneFromView) {
                // To Database
                return str_replace("-", "", $phoneFromView);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
