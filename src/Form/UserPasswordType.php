<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UserPasswordType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
        ->add('plainPassword', RepeatedType::class,[
            "attr"  =>  [
                "class" =>'form-control',
            ],
            "type"  =>  PasswordType::class,
            'invalid_message' => 'Le mot de passe n\'est pas identique',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => [
                'label' => 'Password',
                "label_attr"    =>  [
                    "class" =>  "form-label"
                ],
                "attr"  =>  [
                    "class" =>"form-control",
                ],
                
            ],
            'second_options' => [
                'label' => 'Vérification du Password',
                "label_attr"    =>  [
                    "class" =>  "form-label"
                ],
                "attr"  =>  [
                    "class" =>"form-control",
                ],
            ],
        ])
        ->add('newPassword', PasswordType::class,[
            "attr"  =>  [
                "class" =>'form-control',
            ],
            'label' => 'Nouveau Password',
            "label_attr"    =>  [
                "class" =>  "form-label mt-4"
            ],
            'constraints'   =>  [new Assert\NotBlank()]
        ])
            ->add('submit', SubmitType::class,[
                'attr'  =>  [
                    'class' =>  'btn btn-primary mt-4'
                ],
                'label' =>  'placeholder'
            ]);
    }
}