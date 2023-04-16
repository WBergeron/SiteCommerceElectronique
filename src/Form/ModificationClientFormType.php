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

class ModificationClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('modifier', SubmitType::class, [
                'label' => "Sauvegarder",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
