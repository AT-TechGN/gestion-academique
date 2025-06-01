<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Emploi;
use App\Entity\Enseignant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class EmploiTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salle', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Salle'
            ])
            ->add('jour', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Jour'
            ])
            ->add('heureDebut', TimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Heure de début'
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Heure de fin'
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
                'label' => 'Cours'
            ])
            ->add('enseignant', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function($enseignant) {
                    $user = $enseignant->getUser();
                    return $user ? $user->getEmail() : 'Enseignant';
                },
                'attr' => ['class' => 'form-select'],
                'label' => 'Enseignant'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emploi::class,
        ]);
    }
}