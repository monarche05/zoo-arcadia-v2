<?php

namespace App\Form;

use App\Entity\AvisClients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', null, [
                'label' => 'Pseudonyme',
                'attr' => ['class' => 'form-control mb-3', 'id' => 'homePseudoModal'],
                'required' => true,
            ])
            ->add('text', null, [
                'label' => 'Avis',
                'attr' => ['class' => 'form-control mb-3', 'id' => 'homeTextModal'],
                'required' => true,
            ])
            
            ->add('note', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    '-- Choisissez une note --' => null,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'attr' => ['class' => 'form-select', 'id' => 'homeNoteModal'],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvisClients::class,
        ]);
    }
}
