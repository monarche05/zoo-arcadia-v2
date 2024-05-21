<?php

namespace App\Controller\Admin;

use App\Entity\Animals;
use App\Entity\AvisClients;
use App\Entity\CommentHabitat;
use App\Entity\Habitat;
use App\Entity\MailClients;
use App\Entity\RapportAnimal;
use App\Entity\RapportNourriture;
use App\Entity\Schedules;
use App\Entity\Services;
use App\Entity\User;
use App\Service\DataService;
use App\Service\RedisService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Response as OAResponse;


class AdminController extends AbstractDashboardController
{
    private $dataService;
    private $redisService;

    public function __construct(
        DataService $dataService,
        RedisService $redisService,
        )
    {
        $this->dataService = $dataService;
        $this->redisService = $redisService;
    }

    #[Route('/admin', name: 'admin')]
    #[OA\Get(
        path: "/admin",
        summary: "Affiche le tableau de bord en fonction du rôle de l'utilisateur",
        tags: ["Administration"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Tableau de bord de l'utilisateur selon son rôle"
            ),
            new OAResponse(
                response: 401,
                description: "Non autorisé",
            ),
            new OAResponse(
                response: 403,
                description: "Accès refusé",
            )
        ]
    )]
    public function index(): Response
    {
        // Tableau des entités compléte
        $animals = $this->dataService->getAllAnimals();
        // Derniers éléments des tableaux
        $lastComHabitat = $this->dataService->getLastComHabitat();
        $lastMailClient = $this->dataService->getLastMailClient();
        $lastRapNourriture = $this->dataService->getLastRapNourriture();
        $lastAvisClient = $this->dataService->getLastAvisClient();
        $lastRapAnimals = $this->dataService->getLastRapAnimals();
        
        $currentUserRoles = $this->getUser()->getRoles();
        


        // Récupérer les noms des animaux stockés dans Redis
        $animalNames = $this->redisService->getAnimalNames();
        // Récupérer les compteurs de vues pour chaque animal
        $animalViewCounts = $this->redisService->getAnimalViewCounts();
        // Combiner les noms des animaux et les compteurs de vues dans un tableau
        $animaux = [];
        foreach ($animalNames as $index => $name) {
            $viewCount = $animalViewCounts['animal:' . $name] ?? 0; // Utiliser la clé complète de l'animal pour récupérer la valeur associée dans $animalViewCounts

            // Rechercher l'animal correspondant dans le tableau $animals
            $animal = array_filter($animals, function($item) use ($name) {
                return $item->getName() === $name; // Utiliser la méthode getName() de l'objet Animals pour comparer les noms
            });
            $animal = reset($animal); // Récupérer le premier élément du tableau filtré

            // Ajouter l'image de l'animal au tableau $animaux
            $animaux[] = [
                'name' => $name,
                'viewCount' => $viewCount,
                'image' => $animal->getImg()[0], // Utiliser la méthode getImg() de l'objet Animals pour récupérer les images
            ];
        }
        if (in_array('ROLE_ADMIN', $currentUserRoles) && ($this->isGranted('ROLE_ADMIN'))) {



            return $this->render('admin/dashboard_admin.html.twig',[
                'lastRapAnimals' => $lastRapAnimals,
                'lastComHabitat' => $lastComHabitat,
                'lastRapNourriture' => $lastRapNourriture,
                'lastAvisClient' => $lastAvisClient,
                'lastMailClient' => $lastMailClient,
                'animalCount' => $animaux,
                //Affichage de la liste de tout les animaux associé au nombre de visite de leur page (donnée de redis)
                //Affichage du dernier rapport animal fait.
                //Affichage du dernier rapport nourriture fait.
                //Affichage du dernier commentaire habitat fait
                //Affichage du dernier avis client fait
                //Affichage du dernier mail client fait
            ]);

        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles) && ($this->isGranted('ROLE_EMPLOYE')))  {
            
            return $this->render('admin/dashboard_employe.html.twig', [
                'lastRapNourriture' => $lastRapNourriture,
                'lastMailClient' => $lastMailClient,
                'lastAvisClient' => $lastAvisClient,

                //Le dernier rapport nourriture effectuer.
                //Le nombre d'avis n'étant pas validé.
                //Le nombre de mail en attente de traitement
            ]);
        } elseif (in_array('ROLE_VETERINAIRE', $currentUserRoles) && ($this->isGranted('ROLE_VETERINAIRE')))  {
            return $this->render('admin/dashboard_veterinaire.html.twig', [
                'lastRapAnimals' => $lastRapAnimals,
                'lastComHabitat' => $lastComHabitat,
                //Le dernier rapport animal effectuer
                //Le dernier Commentaire habitat effectuer
                
            ]);
        }

        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Zoo Arcadia')
            ->setTitle('<h2>Dashboard</h2>')
            ->setFaviconPath('favicon.svg')
            ->setTranslationDomain('my-custom-domain')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            ->generateRelativeUrls()
            ->setLocales(['fr', 'en'])
            ->setLocales([
                'fr' => 'fr French',
                'en' => '🇬🇧 English',
            ])
        ;
    }

    public function configureMenuItems(): iterable
    {
        $currentUserRoles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $currentUserRoles) && ($this->isGranted('ROLE_ADMIN'))) {
            // Pour les administrateurs, tout les menus qui sont autorisées
            yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);
            yield MenuItem::linkToCrud('Habitat', 'fa fa-house-flag', Habitat::class);
            yield MenuItem::linkToCrud('Services', 'fa-solid fa-hand-holding-hand', Services::class);
            yield MenuItem::linkToCrud('Animaux', 'fa fa-paw', Animals::class);
            yield MenuItem::linkToCrud('Rapport vétérinaire', 'fa-solid fa-file-signature', RapportAnimal::class);
            yield MenuItem::linkToCrud('Rapport Nourriture', 'fa-solid fa-apple-whole', RapportNourriture::class);
            yield MenuItem::linkToCrud('Horaires', 'fa fa-clock', Schedules::class);
            yield MenuItem::linkToCrud('Avis clients', 'fa fa-users-rectangle', AvisClients::class);
            yield MenuItem::linkToCrud('Message clients', 'fa fa-envelope', MailClients::class);
            yield MenuItem::linkToCrud('Commentaire habitat', 'fa fa-comments', CommentHabitat::class);
        } elseif (in_array('ROLE_EMPLOYE', $currentUserRoles) && ($this->isGranted('ROLE_EMPLOYE')))  {
            // Pour les employés, tout les menus qui sont autorisées
            yield MenuItem::linkToCrud('Rapport Nourriture', 'fa-solid fa-apple-whole', RapportNourriture::class);
            yield MenuItem::linkToCrud('Avis clients', 'fa fa-users-rectangle', AvisClients::class);
            yield MenuItem::linkToCrud('Message clients', 'fa fa-envelope', MailClients::class);
            yield MenuItem::linkToCrud('Services', 'fa-solid fa-hand-holding-hand', Services::class);
        } elseif (in_array('ROLE_VETERINAIRE', $currentUserRoles) && ($this->isGranted('ROLE_VETERINAIRE')))  {
            // Pour les vétérinaires, tout les menus qui sont autorisées
            yield MenuItem::linkToCrud('Commentaire habitat', 'fa fa-comments', CommentHabitat::class);
            yield MenuItem::linkToCrud('Rapport vétérinaire', 'fa-solid fa-file-signature', RapportAnimal::class);
            yield MenuItem::linkToCrud('Rapport Nourriture', 'fa-solid fa-apple-whole', RapportNourriture::class);
        };

    }
}
