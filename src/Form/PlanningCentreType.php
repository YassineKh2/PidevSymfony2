<?php

namespace App\Form;

use App\Entity\PlanningCentre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningCentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebutPlanning')
            ->add('DateFinPlanning')
            ->add('Description')
            ->add('Titre')
            ->add('Centre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanningCentre::class,
        ]);
    }
}
