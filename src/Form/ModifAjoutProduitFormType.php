<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\CallbackTransformer;

class ModifAjoutProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => []
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'categorie',
                'label' => 'Catégorie'
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Prix de vente',
                'html5' => true,
                'attr' => []
            ])
            ->add('quantity_in_stock', NumberType::class, [
                'required' => true,
                'label' => 'Quantité en inventaire',
                'attr' => []
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description du produit',
                'attr' => []
            ])
            ->add('image_path', FileType::class, [
                'required' => false,
                'label' => 'Image du produit',
                'attr' => []
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => "Sauvegarder",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);

        $builder->get('image_path')->addModelTransformer(new CallbackTransformer(
            function ($imagePathFromDataBase) {
                //To View
                return "";
            },
            function ($imagePathFromView) {
                // To Database
                // TODO: Doit retourner l'ancienne string ou faire le nouveau path
                if ($imagePathFromView == null) {
                    return;
                } else {
                    return $imagePathFromView;
                }
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
