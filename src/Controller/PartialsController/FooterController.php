<?php

namespace App\Controller\PartialsController;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Response as OAResponse;
class FooterController extends AbstractController
{
    #[OA\Get(
        path: "/footer",
        summary: "Récupère les données pour le pied de page",
        tags: ["Partials"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Renvoie les données pour le pied de page",
            ),
            new OAResponse(
                response: 404,
                description: "Ressource non trouvée"
            )
        ]
    )]
    public function footerAction(DataService $dataService): Response
    {
        $schedules = $dataService->getSchedules();
        $allServices = $dataService->getAllServices();
        $allHabitats = $dataService->getAllHabitats();

        return $this->render('_partials/_footer.html.twig', [
            'controller_name' => 'FooterController',
            'schedules' => $schedules,
            'Services' => $allServices,
            'Habitats' => $allHabitats,
        ]);
    }
}