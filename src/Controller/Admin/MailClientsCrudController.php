<?php

namespace App\Controller\Admin;

use App\Entity\MailClients;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MailClientsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MailClients::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles))  {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE);
                
        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles))  {
            // Pour les employés, seules certaines actions sont autorisées
            return $actions
                ->disable(Action::EDIT, Action::NEW);
        } else {
            return $actions
                ->disable(Action::EDIT,Action::DELETE, Action::NEW, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
            ->setLabel('Sujet'),
            EmailField::new('mail')
            ->setLabel('E-mail'),
            TextField::new('text')
            ->setLabel('Message'),
            DateField::new('date'),
        ];
    }

}
