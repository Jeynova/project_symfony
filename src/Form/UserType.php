<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class UserType extends AbstractType
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
        ->add('plainPassword',PasswordType::class,[
            "attr"  =>  [
                "class" =>'form-control',
            ],
            'label' => 'Password',
            "label_attr"    =>  [
                "class" =>  "form-label"
            ],
            'required'  =>  true,
        ])
        ->add('submit',SubmitType::class, [
            "attr"  =>  [
                "class" =>'btn btn-primary',
            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
