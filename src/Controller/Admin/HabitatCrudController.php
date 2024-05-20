<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HabitatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles))  {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions;
                
        } else {
            return $actions
                ->disable(Action::EDIT,Action::DELETE, Action::NEW, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            TextField::new('description'),
            ArrayField::new('list_animals'),
            ArrayField::new('img')
        ];
    }
}
