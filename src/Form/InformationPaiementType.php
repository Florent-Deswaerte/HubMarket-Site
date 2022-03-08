<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationPaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label"=>"Email",
                "required" => true
            ])
            ->add('adresse', TextType::class, [
                "label"=>"Adresse"
            ])
            ->add('nom', TextType::class, [
                "label"=>"Nom"
            ])
            ->add('prenom', TextType::class, [
                "label"=>"Prénom"
            ])
            ->add('pays', TextType::class, [
                "label"=>"Pays"
            ])
            ->add('ville', TextType::class, [
                "label"=>"Ville"
            ])
            ->add('codePostal', NumberType::class, [
                "label"=>"Code Postal"
            ])
            ->add('phone', TelType::class, [
                "label"=> "Numéro de Téléphone"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
