<?php

namespace App\Controller;

use App\Entity\AvisClients;
use App\Form\AvisClientType;
use App\Service\DataService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Schema;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    #[OA\Get(
        path: "/home",
        summary: "Affiche la page d'accueil avec les animaux, les habitats, les services et les avis clients",
        tags: ["Accueil"]
    )]
   
    public function index(EntityManagerInterface $entityManager, Request $request, DataService $dataService): Response
    {

        //Récupérer la liste de tout les animaux via le schedules dataService 
        $allHabitats= $dataService->getAllHabitats();
        //Récupérer la liste de tout les animaux via le schedules dataService
        $schedules= $dataService->getSchedules();
        //Récupérer la liste de tout les services via le service dataService
        $allServices = $dataService->getAllServices();
        //Récupérer la liste de tout les animaux via le service dataService
        $allAnimals= $dataService->getAllAnimals();
        //Récupérer la liste de tout les rapports animals via le service dataService
        $allRapAnimals = $dataService->getAllRapAnimals();
        //récupération de formulaire d'avis client
        $avisClients = new AvisClients;
        $form = $this->createForm(AvisClientType::class, $avisClients);
        $form->handleRequest($request);

        //Récupérer une liste de tout les avis validé
        $allAvisValide = $entityManager->getRepository(AvisClients::class)
        ->createQueryBuilder('a')
        ->where('a.valide = :valide')
        ->setParameter('valide', true)
        ->getQuery()
        ->getResult();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'Avis_clients' => $allAvisValide ,
            'form' => $form->createView(),
            'Animals' => $allAnimals,
            'RapAnimals' => $allRapAnimals,
            'Services' =>  $allServices,
            'schedules' => $schedules,
            'Habitats' => $allHabitats,
        ]);
    }

    #[Route('/home/send', name: 'app_home_send', methods:['POST'])]
    #[OA\Post(
        path: "/home/send",
        summary: "Envoie un nouvel avis client",
        tags: ["Accueil"],
        parameters: [
            new Parameter(
                name: "Pseudo",
                in: "query",
                description:"Pseudonyme de l'utilisateur",
                required: true,
                schema: new Schema(type: "string")
            ),
            new Parameter(
                name: "Avis",
                in: "query",
                description:"Avis de l'utilisateur",
                required: true,
                schema: new Schema(type: "string")
            ),
            new Parameter(
                name: "Note",
                in: "query",
                description:"Note donné par l'utilisateur de 1 à 5",
                required: true,
                schema: new Schema(type: "integer")
            ),

        ],
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                type: "object",
                properties: [
                    new Property(
                        property: "pseudo",
                        type: "string",
                        description: "Pseudo de l'auteur de l'avis",
                    ),
                    new Property(
                        property: "avis",
                        type: "string",
                        description: "Contenu de l'avis",
                    ),
                    new property(
                        property: "integer",
                        type: "integer",
                        description: "Note de l'avis (entre 1 et 5)",
                    ) 
                ] 
            )
        ),
        responses: [
            new OAResponse(
                response: 200,
                description: "L'avis a été envoyé avec succès",
            ),
            new OAResponse(
                response: 500,
                description: "L'avis n'a pas été envoyé"
            )
        ]
    )]
        
    public function sendAvis(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Récupérer un nouvelle avis client et l'envoyer dans la BDD avec la valeur "valide" à 0.
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $newPseudo = htmlspecialchars($data['pseudo']) ;
            $newAvis = htmlspecialchars($data['avis']);
            $newNote = htmlspecialchars($data['note']);

            // génération de la date d'envoie de l'avis
            $date = new DateTime();
            //enregistrement
            $avisClient = new AvisClients;
            $avisClient->setPseudo($newPseudo);
            $avisClient->setText($newAvis);
            $avisClient->setNote($newNote);
            $avisClient->setDate($date);
            $avisClient->setValide(false);

            $entityManager->persist($avisClient);
            $entityManager->flush();
        }

        return $this->json(['success' => true]);
    }

    #[Route('/home/confirmation', name:"app_home_confirmation")]
    #[OA\Get(
        path: "/home/confirmation",
        summary: "Affiche la page de confirmation d'envoi d'un avis client",
        tags: ["Accueil"]
    )]
    public function confirmationAvis(): Response
    {
        return $this->render('home/confirmation_avis.html.twig');
    }
}
