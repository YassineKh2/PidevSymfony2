<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Organisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomEvenement')
            ->add('DateEvenement')
            ->add('NombreParticipantEvenement')
            ->add('PrixEvenement')
            ->add('TypeEvenement')
            ->add('Description')
            ->add('Organisateur', EntityType::class ,[
                'class' => Organisateur::class,
                'choice_label'=> 'NomOrganisateur',
                'multiple'=>false,
                'expanded'=>false,
            ])
            ->add('Imageevenement', FileType::class, [
                'label' => 'Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Donner une image valide',
                    ])
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
