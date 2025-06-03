<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Document;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom',
                'label' => 'Cours associé',
                'attr' => ['class' => 'form-select']
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Cours' => 'cours',
                    'TD' => 'td',
                    'TP' => 'tp',
                    'Autre' => 'autre'
                ],
                'label' => 'Type de document',
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}