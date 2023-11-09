<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomRue')
            ->add('NumRue')
            ->add('CodePostal')
            ->add('Gouvernorat')
            ->add('latitude')
            ->add('longitude')

//            ->add('therapist')
//            ->add('organisateur')
//            ->add('centre')
//            ->add('evenement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
