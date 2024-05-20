<?php

namespace App\Controller\Admin;

use App\Entity\CommentHabitat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentHabitatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommentHabitat::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles))  {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE);
                
        } elseif (in_array('ROLE_VETERINAIRE', $currentUserRoles))  {
            // Pour les employés, seules certaines actions sont autorisées
            return $actions
                ->disable(Action::DELETE, Action::EDIT);
        } else {
            return $actions
                ->disable(Action::EDIT,Action::DELETE, Action::NEW, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            AssociationField::new('habitat')->renderAsNativeWidget(),
            TextField::new('detail')
            ->setLabel('Commentaire'),
            TextField::new('state')
            ->setLabel('État'),
            BooleanField::new('improvement')
            ->setLabel('Amélioration'),
            DateField::new('date')
            ->hideWhenUpdating(),
        ];
    }
    
}
