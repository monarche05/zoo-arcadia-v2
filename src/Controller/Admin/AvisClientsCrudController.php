<?php

namespace App\Controller\Admin;

use App\Entity\AvisClients;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AvisClientsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AvisClients::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles) || in_array('ROLE_VETERINAIRE', $currentUserRoles)) {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::DETAIL);
        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles))  {
            // Pour les employés, seules certaines actions sont autorisées
                
            return $actions
                ->disable( Action::NEW );
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo')
            ->setLabel('Pseudonyme')
            ->hideWhenUpdating(),
            TextField::new('text')
            ->setLabel('Message')
            ->hideWhenUpdating(),
            DateField::new('date')
            ->hideWhenUpdating(),
            IntegerField::new('note')
            ->hideWhenUpdating(),
            BooleanField::new('valide')
            ->setLabel('Validation'),
        ];
    }
}
