<?php

namespace App\Controller\Admin;

use App\Entity\Roles;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Choice;

class UserCrudController extends AbstractCrudController
{
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles)) {
            // Pour les administrateurs, toutes les actions sont autorisées
            return $actions;
        } else  {
            return $actions
                ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::DETAIL);
        }
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('name'),
            TextField::new('firstname'),
            EmailField::new('mail'),
            TextField::new('password')
            ->onlyOnForms()
            ->setFormType(PasswordType::class),
            IntegerField::new('roles', 'Roles')
            ->formatValue(function ($value, $entity) {
                // Formater la valeur pour afficher les rôles sous forme de texte
                $roles = $entity->getRoles();
                $formattedRoles = [];
                foreach ($roles as $role) {
                    $formattedRoles[] = ucfirst(strtolower(str_replace('ROLE_', '', $role)));
                }
                return implode(', ', $formattedRoles);
            })
            ->hideWhenUpdating()
            ->hideWhenCreating(),
            AssociationField::new ('role', 'Roles')
            ->onlyOnForms()
            ->setRequired(true)
            ->setFormTypeOption(
                'query_builder',
                function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('r')
                        ->where('r.name != :role')
                        ->setParameter('role', 'ROLE_ADMIN')
                        ->orderBy('r.name', 'ASC');
                }
            )
            ->setFormTypeOption(
                'choice_label',
                'name'
            )
            ->setFormTypeOption(
                'choice_value',
                'id'
            ),
            BooleanField::new('is_verified'),
        ];
    }
}
