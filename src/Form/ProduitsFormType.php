<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['required' => true, 'attr'=>['placeholder' => 'Nom']])
            ->add('qty', IntegerType::class, ['required' => true, 'attr'=> ['min'=>0, 'placeholder' => 'Quantité']])
            ->add('prix', MoneyType::class, ['required'=>true, 'attr'=>['placeholder' => 'Prix']])
            ->add('fournisseur', TextType::class, ['required' => true, 'attr'=>['placeholder' => 'Fournisseur']])
            ->add('categorie', TextType::class, ['required' => true, 'attr'=>['placeholder' => 'Catégorie']])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
