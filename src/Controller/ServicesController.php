<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes as OA;

class ServicesController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    #[OA\Get(
        path: "/services",
        summary: "Récupère la liste de tous les services, habitats et horaires",
        tags: ["Services"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Renvoie la liste de tous les services, habitats et horaires",
            ),
            new OAResponse(
                response: 404,
                description: "Ressource non trouvée",
            )
        ]
    )]
    public function index(DataService $dataService): Response
    {
        //Récupérer la liste de tout les animaux via le schedules dataService 
        $allHabitats= $dataService->getAllHabitats();
        //Récupérer la liste de tout les animaux via le schedules dataService
        $schedules= $dataService->getSchedules();
        //Récupérer la liste de tout les services via le service dataService
        $allServices = $dataService->getAllServices();

        return $this->render('services/index.html.twig', [
            'controller_name' => 'ServicesController',
            'Services' =>  $allServices,
            'schedules' => $schedules,
            'Habitats' => $allHabitats,
        ]);
    }
}
