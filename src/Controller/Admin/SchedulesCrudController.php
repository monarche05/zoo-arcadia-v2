<?php

namespace App\Controller\Admin;

use App\Entity\Schedules;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class SchedulesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedules::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles)) {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions;
        } else {
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ArrayField::new('days'),
            TimeField::new('opening_time'),
            TimeField::new('closing_time'),
        ];
    }

}
