<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
            ->add('date_enregistrement')
            ->add('etat', ChoiceType::class, [
                "choices" => [
                    'en attente' => 'en attente',
                    'en cours' => 'en cours',
                    'livrée' => 'livrée'
                ]
            ])
            ->add('membre', EntityType::class, [
                "class" => Membre::class,
                "choice_label" => function ($membre) {
                    return $membre->getNom() . " " . $membre->getPrenom();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
