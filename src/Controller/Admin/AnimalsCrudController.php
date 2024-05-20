<?php

namespace App\Controller\Admin;

use App\Entity\Animals;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animals::class;
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
            TextField::new('species'),
            ArrayField::new('img'),
            // ArrayField::new('img'), // Champ img de type ArrayField
            // ArrayField::new('img') // Chaque élément du tableau img est affiché comme une image
            // ->setBasePath('public\img')
            // ->setUploadDir('public\img')
            // ->setUploadedFileNamePattern('[randomhash].[extension]')
            // ->setRequired(false),
            // ImageField::new('img')->setBasePath('\public\img\upload')->setUploadDir('\public\img\upload')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
        ];
    }
}
