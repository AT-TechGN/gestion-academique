<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Etudiant;
use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class NoteTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => function($etudiant) {
                    $user = $etudiant->getUser();
                    $email = $user ? $user->getEmail() : 'Utilisateur inconnu';
                    return $email . ' - ' . $etudiant->getMatricule();
                },
                'attr' => ['class' => 'form-select'],
                'label' => 'Étudiant'
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
                'label' => 'Cours'
            ])
            ->add('note', NumberType::class, [
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 20,
                    'step' => 0.25
                ],
                'label' => 'Note (/20)'
            ])
            ->add('dateEval', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de l\'évaluation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}