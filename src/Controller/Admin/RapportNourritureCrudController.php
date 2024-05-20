<?php

namespace App\Controller\Admin;

use App\Entity\RapportNourriture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RapportNourritureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RapportNourriture::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles) || in_array('ROLE_VETERINAIRE', $currentUserRoles))  {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE);
                
        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles))  {
            // Pour les employés, seules certaines actions sont autorisées
            return $actions
                ->disable(Action::EDIT,Action::DELETE);
        }
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('animal')
            ->setLabel('Images')
            ->setBasePath('/img/')
            ->formatValue(function ($value, $entity) {
                $images = $entity->getAnimal()->getImg();
                $imageUrls = [];
                foreach ($images as $image) {
                    $imageUrls[] = '/img/' . $image;
                }
                return  $imageUrls[0];
            })
            ->onlyOnIndex()
            ,
            AssociationField::new('animal')
            ->renderAsNativeWidget()
            ->formatValue(fn ($value, $entity) => $entity->getAnimal()->getName()),
            TextField::new('nourriture'),
            IntegerField::new('qte')
            ->setLabel('Quantité en g'),
            DateField::new('date'),
            TimeField::new('time')
            ->setLabel('Heure')
        ];
    }
    
}
