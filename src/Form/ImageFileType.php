<?php

namespace App\Form;

use App\Entity\AbstractImageFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', VichImageType::class, [
                'label'=>'Veuillez ajouter une photo (facultatif, maximum 2Mo)',
                'required' => false,
                'allow_delete' => false,
                'download_link' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbstractImageFile::class,
        ]);
    }
}
