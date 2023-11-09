<?php

namespace App\Form;

use App\Entity\ActiviteCentre;
use App\Entity\planningCentres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteCentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('JourActivite')
            ->add('NomActivite')
            ->add('ContenuActivite')
            ->add('HeureDebutActivite')
            ->add('HeureFinActivite')
            ->add('NombreParticipantActiviteMax')
            ->add('Planning')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActiviteCentre::class,
        ]);
    }
}
