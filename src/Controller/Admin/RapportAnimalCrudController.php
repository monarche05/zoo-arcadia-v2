<?php

namespace App\Controller\Admin;

use App\Entity\RapportAnimal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RapportAnimalCrudController extends AbstractCrudController
{
   
    public static function getEntityFqcn(): string
    {
        return RapportAnimal::class;
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
            TextField::new('detail')
            ->setLabel('État'),
            TextField::new('nourriture'),
            IntegerField::new('qte')
            ->setLabel('Quantité en g'),
            DateField::new('date'),
        ];
    }
    
}
