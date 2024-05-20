<?php

namespace App\Form;

use App\Entity\MailClients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control mb-3', 'id' => 'contactTitre'],
                'required' => true,
            ])
            ->add('mail', null, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'id' => 'contactMail',
                    'type' => 'email'
                ],
                'required' => true,
            ])
            ->add('text', null, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'id' => 'contactText',
                    'rows' => '3'
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailClients::class,
            'attr' => ['class' => 'col-11 col-md-10 col-lg-9']
        ]);
    }
}
