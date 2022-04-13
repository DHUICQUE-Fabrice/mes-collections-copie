<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            if ($options['attr']){
            $builder
                ->add('name', TextType::class,[
                    'label'=>'Pseudo : ',
                    'disabled'=>true,
                    'attr'=>[
                        'value'=>$options['attr']['userName'],
                    ]
                ])
                ->add('email', EmailType::class,[
                    'label'=>'Adresse mail : ',
                    'disabled'=>true,
                    'attr'=>[
                        'value'=>$options['attr']['userEmail'],
                    ]
                ]);
        }else{
            $builder
                ->add('name', TextType::class,[
                    'label'=>'Pseudo : ',
                    'attr'=>[
                        'placeholder'=>'Votre pseudo',
                    ]
                ])
                ->add('email', EmailType::class,[
                    'label'=>'Adresse mail : ',
                    'attr'=>[
                        'placeholder'=>'Votre adresse mail',
                    ]
                ])

            ;
        }
        $builder->add('message', TextareaType::class, [
            'label'=>'Votre message : ',
            'attr'=>[
                'rows'=>'10',
            ]
        ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
