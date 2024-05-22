<?php

namespace App\Controller;

use App\Entity\MailClients;
use App\Form\ContactType;
use App\Service\DataService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response as OAResponse;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    #[OA\Get(
        path: "/contact",
        summary: "Affiche la page de contact",
        tags: ["Contact"]
    )]
    public function index(DataService $dataService, Request $request): Response
    {
        //Récupérer la liste de tout les habitats via le service dataService
        $allHabitats= $dataService->getAllHabitats();
        //Récupérer la liste de tout les Schedules via le service dataService
        $schedules= $dataService->getSchedules();
        //Récupérer la liste de tout les services via le service dataService
        $allServices = $dataService->getAllServices();

        $mailClients = new MailClients();
        $form = $this->createForm(ContactType::class, $mailClients);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_contact_confirmation');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ContactController',
            'Habitats' => $allHabitats,
            'Services' =>  $allServices,
            'schedules' => $schedules,
        ]);
    }
    #[Route('/contact/send', name: 'app_contact_send', methods:['POST'])]
    #[OA\Post(
        path: "/contact/send",
        summary: "Envoie un mail via le formulaire de contact",
        tags: ["Contact"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new property(
                        property: "titre",
                        type: "string",
                        description: "Titre de l'e-mail",
                    ),
                    new property(
                        property: "mail",
                        type: "string",
                        description: "Adresse e-mail de l'expéditeur",
                    ),
                    new property(
                        property: "msg",
                        type: "string",
                        description: "Contenue de l'e-mail"
                    )
                ]
            )
        ),
        responses: [
            new OAResponse(
                response: 200,
                description: "Le mail a été envoyé avec succès",
                content: new JsonContent(
                    type: "object",
                    properties: [
                        new property(
                            property: "successe",
                            type: "boolean",
                            description: "Indique si l'opération a réussi",
                        )
                    ]
                )
            ),
            new OAResponse(
                response: "default",
                description: "Une erreur s'est produite lors de l'envoi du mail",
                content: new JsonContent(
                    type: "object",
                    properties: [
                        new property(
                            property: "success",
                            type: "boolean",
                        ),
                        new property(
                            property: "error",
                            type: "string",
                        )

                    ]
                )
            )
        ]
    )]

    public function sendMail(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
       //Récupérer un nouveau mail client et l'envoyer dans la BDD .
        if ($request->isMethod('POST')) {
        $data = json_decode($request->getContent(), true);
        $newTitre = htmlspecialchars($data['titre']) ;
        $newMail = htmlspecialchars($data['mail']);
        $newMsg = htmlspecialchars($data['msg']);

        // génération de la date d'envoie de l'avis
        $date = new DateTime();
        // enregistrement
        $msgClient = new MailClients;
        $msgClient->setTitle($newTitre);
        $msgClient->setMail($newMail);
        $msgClient->setText($newMsg);
        $msgClient->setDate($date);

        $mail = (new TemplatedEmail())
        ->To('contact@demo.fr')
        ->from($newMail)
        ->subject($newTitre)
        ->htmlTemplate('email/contact.html.twig')
        ->context(['data' => $data]);
        $mailer->send($mail);
        
            try 
            {
                $entityManager->persist($msgClient);
                $entityManager->flush();
                return $this->json(['success' => true]);
            } 
            catch (\Exception $e) 
            {
                return $this->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    #[Route('/contact/confirmation', name:"app_contact_confirmation")]
    #[OA\Get(
        path: "/contact/confirmation",
        summary: "Affiche la page de confirmation d'envoi de mail",
        tags: ["Contact"]
    )]
    public function confirmationContact(): Response
    {
        return $this->render('contact/confirmation_contact.html.twig');
    }
}
