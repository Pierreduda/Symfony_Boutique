<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'message' => "L'adresse {{ value }} n'est pas une adresse mail valide",
                    ]),
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new PasswordStrength([
                        "minStrength" => 4,
                        "message" => "Le mot de passe doit contenir au moins 8 caractères dont une minuscule, une majuscule, un chiffre et un caractère spécial"
                    ]),
                ]
            ])
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Vendeur' => 'ROLE_SELLER',
                    'Membre' => 'ROLE_USER'
                ],
                "multiple" => true,
                "expanded" => true
            ])
            ->add('nom', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "Le nom doit comporter 50 caractères maximum",
                    ]),
                    new NotBlank([
                        "message" => "Le nom ne peut pas être vide"
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                "label" => "Prénom",
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "Le prénom doit comporter 50 caractères maximum",
                    ]),
                    new NotBlank([
                        "message" => "Le prénom ne peut pas être vide"
                    ])
                ]
            ])
            ->add('adresse', TextareaType::class, [
                "constraints" => [
                    new Length([
                        "max" => 255,
                        "maxMessage" => "L'adresse doit comporter 255 caractères maximum",
                    ]),
                    new NotBlank([
                        "message" => "L'adresse ne peut pas être vide"
                    ])
                ]
            ])
            ->add('cp', TextType::class, [
                "label" => "Code Postal",
                "constraints" => [
                    new Regex([
                        "pattern" => "#^((0[1-9])|([1-8][0-9])|(9[0-8]))[0-9]{3}$#",
                        "message" => "Le code postal n'est pas un code postal valide"
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 100,
                        "maxMessage" => "La ville doit comporter 100 caractères maximum",
                    ]),
                    new NotBlank([
                        "message" => "La ville ne peut pas être vide"
                    ])
                ]
            ])
            ->add('tel', TelType::class, [
                "label" => "Téléphone *",
                "required" => false,
                "constraints" => [
                    new Length([
                        "max" => 12,
                        "maxMessage" => "Le téléphone doit comporter 12 caractères maximum",
                    ])
                ]
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
