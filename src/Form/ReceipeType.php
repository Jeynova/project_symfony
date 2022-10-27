<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Receipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class ReceipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                "attr"  =>  [
                    "class" =>'form-control',
                    'minlength' =>  2,
                    'maxlength' =>  50
                ],
                "label" =>  "Nom",
                "label_attr"    =>  [
                    "class" =>  'form-label mt-4'
                ],
                'constraints'   =>  [
                    new Assert\Length(["min" => 2, "max"=> 50])
                ]
            ])
            /* ->add('time', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
                'with_seconds'  => true,
                'placeholder' => [
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ],
            ]) */
            ->add('time', IntegerType::class, [
                "attr"  =>  [
                    "class" =>'form-control',
                ],
                "label" =>  "Temps ( en minutes )",
                "label_attr"    =>  [
                    "class" =>  'form-label mt-4'
                ],
                'constraints'   =>  [
                    new Assert\Length(["min" => 1, "max"=> 1440]),
                ]
            ])
            ->add('people',IntegerType::class, [
                "attr"  =>  [
                    "class" =>'form-control',
                ],
                "label" =>  "Nb de personne",
                "label_attr"    =>  [
                    "class" =>  'form-label mt-4'
                ],
                'constraints'   =>  [
                    new Assert\Length(["min" => 1, "max"=> 50]),
                    new Assert\Positive(),
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'N/A' => null,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'choice_attr' => [
                    'N/A'   => ['style' => 'color :Black'],
                    '1'     => ['style' => 'color : #2196F3'],
                    '2'     => ['style' => 'color : #4CAF50'],
                    '3'     => ['style' => 'color : #FFEB3B'],
                    '4'     => ['style' => 'color : #FF9800'],
                    '5'     => ['style' => 'color : #E53935'],
                ],
                "attr"  =>  [
                    "class" =>'form-control',
                ],
                "label" =>  "Difficulté",
                "label_attr"    =>  [
                    "class" =>  'form-label'
                ],
                'constraints'   =>  [
                    new Assert\Positive(),
                    new Assert\LessThan(6)
                ]
            ])
            ->add('description', TextareaType::class, [
                "attr"  =>  [
                    "class" =>'form-control',
                ]
            ])
            ->add('price',MoneyType::class, [
                "attr"  =>  [
                    "class" =>'form-control',
                ],
                "label" =>  "Prix",
                "label_attr"    =>  [
                    "class" =>  'form-label'
                ],
                'constraints'   =>  [
                    new Assert\Positive(),
                ]
            ])
            ->add('isfavorite', CheckboxType::class, [
                'label'    => 'Voulez vous ajouter aux favoris ?',
                "label_attr"    =>  [
                    "class" =>  'form-check-label'
                ],
                'required' => false,
                "attr"  =>  [
                    "class" =>'form-check-input',
                ]
            ])
            ->add('ingredients', EntityType::class, [
                // looks for choices from this entity
                'label'    => 'Les ingredients',
                'class' => Ingredient::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
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
            'data_class' => Receipe::class,
        ]);
    }
}
