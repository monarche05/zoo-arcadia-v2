<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use App\Entity\User;

class UserListener
{
    private $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Si l'entité ajoutée est un utilisateur
        if ($entity instanceof User) {
            // Hasher le mot de passe
            $plainPassword = $entity->getPassword();
            $hashedPassword = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash($plainPassword);
            $entity->setPassword($hashedPassword);
        }
    }
}