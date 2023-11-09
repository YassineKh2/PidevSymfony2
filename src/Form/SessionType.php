<?php

namespace App\Form;

use App\Entity\Despense;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('DateDebutSession')
            ->add('DateFinSession')
            ->add('NombreParticipantSession')
            ->add('Difficulte',ChoiceType::class, [
        'choices'  => [
            'Simple' => 'd1',
            'Medium' => 'd2',
            'Difficile' => 'd3',
        ]])
            ->add('DescriptionSession')
            ->add('NomSession')
            ->add('Despense',EntityType::class, [
                // looks for choices from this entity
                'class' => Despense::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'id',
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('ImageSession', FileType::class, [
                'label' => 'Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [new NotBlank([
                    'message' => 'La photo de Session est obligatoire',
                ]),
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Donner une image valide',
                    ])
                ],
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
