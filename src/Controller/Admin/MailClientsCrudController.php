<?php

namespace App\Controller\Admin;

use App\Entity\MailClients;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailClientsCrudController extends AbstractCrudController
{

    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

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
                ->disable(Action::NEW);
        } else {
            return $actions
                ->disable(Action::EDIT,Action::DELETE, Action::NEW, Action::DETAIL);
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
            ->setLabel('Sujet')
            ->hideWhenUpdating(),
            EmailField::new('mail')
            ->setLabel('E-mail')
            ->hideWhenUpdating(),
            TextField::new('text')
            ->setLabel('Message')
            ->hideWhenUpdating(),
            DateField::new('date')
            ->hideWhenUpdating(),
            TextareaField::new('response')
            ->setLabel('Réponse')
            
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Récupérer la création de compte employé depuis le formulaire et l'associer à l'entité
        $response = $entityInstance->getResponse();
        $entityInstance->setResponse($response);

        // Enregistrer l'entité modifiée
        parent::updateEntity($entityManager, $entityInstance);

        // Récupérer les informations sur le message auquel l'employé répond
        $clientEmail = $entityInstance->getMail();
        $subject = $entityInstance->getTitle();
        $message = $entityInstance->getText();

        // Envoyer un e-mail au client avec la réponse de l'employé
        $this->sendResponseEmail($this->mailer, $clientEmail, $subject, $message, $response);
    }

    private function sendResponseEmail(MailerInterface $mailer, $clientEmail, $subject, $message, $response): void
    {

        $date = new DateTime();
        // enregistrement
        $responseClient = new MailClients;
        $responseClient->setTitle($subject) ;
        $responseClient->setMail($clientEmail);
        $responseClient->setText($message);
        $responseClient->setResponse($response);
        $responseClient->setDate($date);

        

        $mail = (new TemplatedEmail())
        ->To($responseClient->getMail())
        ->from('contact@demo.fr')
        ->subject($responseClient->getTitle())
        ->htmlTemplate('email/response.html.twig')
        ->context(['data' => $responseClient]);
        $mailer->send($mail);
    }

}
