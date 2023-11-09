<?php

namespace App\Form;

use App\Entity\Centre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomCentre')
            ->add('CapaciteCentre')
            ->add('NombreBlocCentre')
            ->add('localisation')
           // ->add('Adresse')
           ->add('img', FileType::class, [
               'label' => 'Picture',
               'mapped' => false,
               'required' => false,
               'constraints' => [new NotBlank([
                   'message' => 'La photo de Centre est obligatoire',
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Centre::class,
        ]);
    }
}
