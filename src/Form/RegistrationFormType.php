<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['attr']){
            $builder
                ->add('name', TextType::class, [
                    'label' => 'Pseudo : ',
                    'attr' => [
                        'placeholder' => 'Pseudo',
                        'value'=>$options['attr']['userName'],
                    ],
                ])->add('email', EmailType::class, [
                    'label' => 'Adresse mail : ',
                    'attr' => [
                        'placeholder' => 'Adresse mail',
                        'value'=>$options['attr']['userEmail'],
                    ],
                ]);
        }else{
            $builder
                ->add('name', TextType::class, [
                    'label' => 'Pseudo : ',
                    'attr' => [
                        'placeholder' => 'Pseudo',
                    ],
                ])->add('email', EmailType::class, [
                    'label' => 'Adresse mail : ',
                    'attr' => [
                        'placeholder' => 'Adresse mail',
                    ],
                ]);
        }


        $builder->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
