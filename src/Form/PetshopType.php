<?php

namespace App\Form;

use App\Entity\Petshop;
use App\Entity\PetshopSize;
use App\Entity\PetshopSpecies;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PetshopType extends AbstractType
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
                    'rows' => '6',
                    'placeholder' => 'Biographie',
                    'class' => 'fonttext',
                ],
            ])
            ->add('size', EntityType::class,[
                'class'=>PetshopSize::class,
                'choice_label'=>'name',
                'label'=>'Taille : ',
                'placeholder'=>'Sélectionnez',
                'attr' => [
                    'class' => 'fonttext',
                ],
            ])
            ->add('species', EntityType::class,[
                'class'=>PetshopSpecies::class,
                'choice_label'=>'name',
                'label'=>'Animal : ',
                'placeholder'=>'Sélectionnez',
                'attr' => [
                    'class' => 'fonttext',
                ],
            ])
            ->add('file', VichImageType::class, [
                'label'=>'Veuillez ajouter une photo (facultatif, maximum 1Mo)',
                'required' => false,
                'allow_delete' => false,
                'image_uri' => false,
                'download_link' => false,
                'attr' => [
                    'src' => '',
                    'class' => 'fonttext',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Petshop::class,
        ]);
    }
}
