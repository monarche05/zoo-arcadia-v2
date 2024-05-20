<?php

namespace App\Controller;

use App\Entity\Animals;
use App\Entity\Habitat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\DataService;
use App\Service\RedisService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;

class AnimauxController extends AbstractController
{
    
    #[Route('/animaux', name: 'app_animaux', methods: ['GET'])]
    #[OA\Get(
        path: "/animaux",
        summary: "Renvoie les données nécessaires à l'affichage des différents animaux",
        tags: ["Animaux"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Réponse avec la liste de tous les animaux",
                content: new JsonContent(
                    type: "array",
                    items: new Items(ref: new Model(type: Animals::class))
                )
            )
        ]
    )]
    public function index(DataService $dataService): Response
    {
        //Récupérer la liste de tout les animaux via le service dataService
        $allAnimals = $dataService->getAllAnimals();
        //Récupérer la liste de tout les habitats via le service dataService
        $allHabitats= $dataService->getAllHabitats();
        //Récupérer la liste de tout les Schedules via le service dataService
        $schedules= $dataService->getSchedules();
        //Récupérer la liste de tout les services via le service dataService
        $allServices = $dataService->getAllServices();
        //Récupérer la liste de tout les rapport animal via le service dataService
        $allRapAnimal = $dataService->getAllRapAnimals();


        //Récupére les nom des espéces dans un tableau
        $allSpecies = [];
        foreach ($allAnimals as $species){
            $allSpecies[] = $species->getSpecies();
        }

        $nameHabitats = [];
        foreach ($allHabitats as $habitat ) {
            $nameHabitats[]= $habitat->getName();
        }
        
        return $this->render('animaux/index.html.twig', [
            'controller_name' => 'AnimauxController',
            'animals' => $allAnimals,
            'Habitats' => $allHabitats,
            'allSpecies' => $allSpecies,
            'Services' =>  $allServices,
            'schedules' => $schedules,
            'allRapAnimal' => $allRapAnimal
        ]);
    }
    #[Route('/animaux/filtrer', name: 'app_animaux_filtre', methods: ['GET'])]
    #[OA\Get(
        path: "/animaux/filtrer",
        summary: "Filtre les animaux en fonction de l'habitat",
        tags: ["Animaux"],
        parameters: [
            new OA\Parameter(
                name: "habitat",
                in: "query",
                description: "Nom de l'habitat à filtrer",
                required: false,
                schema: new Schema(type: "string")
            ),
        ],
        responses: [
            new OAResponse(
                response: 200,
                description: "Réponse avec la liste des animaux filtrés",
                content: new JsonContent(
                    type: "array",
                    items: new Items(ref: new Model(type: Animals::class))
                )
            )
        ]
    )]
    public function filtrerAnimaux(Request $request, EntityManagerInterface $entityManager,DataService $dataService): JsonResponse
    {
        $habitatIdRequest = $request->query->get('habitat');
        $allRapAnimal = $dataService->getAllRapAnimals();

        if ($habitatIdRequest === 'all') {
            // Récupérer tous les animaux
            $animals = $entityManager->getRepository(Animals::class)->findAll();
        } else {
            $habitat = $entityManager->getRepository(Habitat::class)->find($habitatIdRequest);

            if (!$habitat) {
                // Gérer le cas où l'habitat n'est pas trouvé
                return new JsonResponse(['error' => 'Habitat not found'], Response::HTTP_NOT_FOUND);
            }

            // Récupérer les animaux associés à cet habitat
            $animals = $habitat->getAnimals();
        }

        $speciesId = $request->query->get('species');

        // Si une espèce est sélectionnée, filtrer les animaux en fonction de cette espèce
        if ($speciesId) {
            $filteredAnimals = array_filter($animals, function($animal) use ($speciesId) {
                return $animal->getSpecies() == $speciesId;
            });
        } else {
            $filteredAnimals = $animals;
        }

        $filteredAnimalsData = [];
        foreach ($filteredAnimals as $animal) {
            // Récupérer toutes les informations de l'animal
            $animalData = [
                'id' => $animal->getId(),
                'name' => $animal->getName(),
                'species' => $animal->getSpecies(),
                'img' => $animal->getImg(),
                'habitatName' => $animal->getHabitat() ? $animal->getHabitat()->getName() : null,
                'description' => $animal->getDescription(),
            ];
        
            // Ajouter les données de l'animal à la liste
            $filteredAnimalsData[] = $animalData;
        }
        
        // Renvoyer les animaux filtrés au format JSON
        if ($habitatIdRequest === 'all') {
            $habitatName = null;
        } else {
            $habitatName = $habitat->getName();
        }
        // Créer un tableau de rapports d'animaux
        $rapportAnimalsData = [];
        foreach ($allRapAnimal as $rapportAnimal) {
            $rapportAnimalsData[] = [
                'animalId' => $rapportAnimal->getAnimal()->getId(),
                'detail' => $rapportAnimal->getDetail(),
                'nourriture' => $rapportAnimal->getNourriture(),
                'qte' => $rapportAnimal->getQte(),
                'date' => $rapportAnimal->getDate()->format('Y-m-d'),
            ];
        }


        return $this->json([
            'animals' => $filteredAnimalsData,
            'habitat' => $habitatName,
            'rapAnimal' => $rapportAnimalsData
        ]);
    }
    #[Route('/animaux/increment', name: 'app_animaux_increment', methods: ['POST'])]
    #[OA\Post(
        path: "/animaux/increment",
        summary: "Incrémente le compteur de vues d'un animal",
        tags: ["Animaux"],
        requestBody: new RequestBody(
            description: "Données nécessaires à l'incrémentation du compteur de vues",
            content: new JsonContent(
                type: "object",
                properties: [
                    new Property(
                        property: "animalName",
                        type: "string",
                        description: "Nom de l'animal dont le compteur de vues doit être incrémenté"
                    )
                ]
            )
        ),
        responses: [
            new OAResponse(
                response: 200,
                description: "Réponse indiquant que le compteur de vues a été incrémenté avec succès",
                content: new JsonContent(
                    type: "object",
                    properties: [
                        new Property(
                            property: "success",
                            type: "boolean",
                            description: "Indique si l'opération a réussi"
                        ),
                        new Property(
                            property: "message",
                            type: "string",
                            description: "Message de confirmation"
                        )
                    ]
                )
            )
        ]
    )]
    public function incrementAnimaux (Request $request,RedisService $redisService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer le nom de l'animal à partir de la requête POST
        $animalName = $data['animalName'];

         // Incrémenter le compteur Redis correspondant à l'animal
        $redisService->incrementAnimalViewCount($animalName);

        return $this->json([
            'success' => true,
            'message' => 'Compteur Redis incrémenté avec succès !'
        ]);
    }
}