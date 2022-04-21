<?php

namespace App\Form;

use App\Entity\HorseCoat;
use App\Entity\HorseSchleich;
use App\Entity\HorseSpecies;
use App\Entity\HorseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HorseSchleichType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom : ',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'fonttext',
                ],
            ])
            ->add('biography', TextareaType::class, [
                'label'=>'Biographie : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Biographie',
                    'class' => 'fonttext',
                    'rows' => '6',
                ],
            ])
            ->add('type', EntityType::class, [
                'class'=>HorseType::class,
                'choice_label'=>'name',
                'label'=>'Type : ',
                'placeholder'=>'Sélectionnez',
                'attr' => [
                    'class' => 'fonttext',
                ],
            ])
            ->add('coat', EntityType::class, [
                'class'=>HorseCoat::class,
                'choice_label'=>'name',
                'label'=>'Robe : ',
                'placeholder'=>'Sélectionnez',
                'attr' => [
                    'class' => 'fonttext',
                ],
            ])
            ->add('species', EntityType::class, [
                'class'=>HorseSpecies::class,
                'choice_label'=>'name',
                'label'=>'Race : ',
                'placeholder'=>'Sélectionnez',
                'attr' => [
                    'class' => 'fonttext',
                ],
            ])
            ->add('file', VichImageType::class, [
                'label'=>'Veuillez ajouter une photo (facultatif, maximum 1Mo)',
                'required' => false,
                'allow_delete' => false,
                'download_link' => false,
                'image_uri' => false,
                'attr' => [
                    'class' => 'fonttext',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HorseSchleich::class,
        ]);
    }
}
