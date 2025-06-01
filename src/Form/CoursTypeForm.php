<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CoursTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Intitulé du cours'
            ])
            ->add('code', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Code du cours'
            ])
            ->add('volumeHoraire', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Volume horaire (heures)'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'label' => 'Description',
                'required' => false
            ])
            ->add('enseignants', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function($enseignant) {
                    $user = $enseignant->getUser();
                    return $user ? $user->getEmail() : 'Enseignant';
                },
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-select'],
                'label' => 'Enseignants associés'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}