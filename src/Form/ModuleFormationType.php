<?php

namespace App\Form;

use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\ModuleFormation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomModule')
            ->add('PrerequisModule')
            ->add('DureeModule')
            ->add('ContenuModule')
            ->add('Formation',EntityType::class, [
                // looks for choices from this entity
                'class' => Formation::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'NomFormation',
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModuleFormation::class,
        ]);
    }
}
