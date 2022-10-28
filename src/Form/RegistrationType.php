<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                "attr"  =>  [
                    "class" =>'form-control',
                    'minlength' =>  2,
                    'maxlength' =>  50
                ],
                "label" => "Nom / Prenom",
                "label_attr"    =>  [
                    "class" =>  "form_label"
                ],
                'constraints'   =>  [
                    new Assert\Length(["min" => 2, "max"=> 50]),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('pseudo',TextType::class,[
                "attr"  =>  [
                    "class" =>'form-control',
                    'minlength' =>  2,
                    'maxlength' =>  50
                ],
                "label" => "Pseudo ( Facultatif )",
                "label_attr"    =>  [
                    "class" =>  "form_label"
                ],
                'required'  =>  false,
                'constraints'   =>  [
                    new Assert\Length(["min" => 2, "max"=> 50]),
                ]
            ])
            ->add('email',EmailType::class, [
                "attr"  =>  [
                    "class" =>"form-control",
                    'minlength' =>  2,
                    'maxlength' =>  180
                ],
                "label" => "Email",
                "label_attr"    =>  [
                    "class" =>  "form-label"
                ],
                'constraints'   =>  [
                    new Assert\Length(["min" => 2, "max"=> 180]),
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ]
            ])
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
            ->add('submit',SubmitType::class, [
                "attr"  =>  [
                    "class" =>'btn btn-primary',
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
