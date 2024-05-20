<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class HabitatsController extends AbstractController
{
    #[Route('/habitats', name: 'app_habitats')]
    #[OA\Get(
        path: "/habitats",
        summary: "Récupère la liste de tous les habitats et des animaux associés",
        tags: ["Habitats"]
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

        return $this->render('habitats/index.html.twig', [
            'controller_name' => 'HabitatsController',
            'Services' =>  $allServices,
            'schedules' => $schedules,
            'Habitats' => $allHabitats,
            'Animals' => $allAnimals,
        ]);
    }
}
