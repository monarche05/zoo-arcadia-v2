<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Services::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles)) {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions;
        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles))  {
            // Pour les employés, seules certaines actions sont autorisées
            return $actions
                ->disable(Action::DELETE, Action::NEW);
        } else {
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name')
            ->setLabel('Titre'),
            TextField::new('description'),
            BooleanField::new('free')
            ->setLabel('Gratuité')
        ];
    }
}
