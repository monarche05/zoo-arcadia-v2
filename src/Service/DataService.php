<?php 

namespace App\Service;

use App\Entity\Animals;
use App\Entity\AvisClients;
use App\Entity\CommentHabitat;
use App\Entity\Habitat;
use App\Entity\MailClients;
use App\Entity\RapportAnimal;
use App\Entity\RapportNourriture;
use App\Entity\Schedules;
use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;


class DataService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // fonction pour récupérer tous les animaux depuis la base de données
    public function getAllAnimals()
    {
        $animalRepository = $this->entityManager->getRepository(Animals::class);
        $allAnimals = $animalRepository->findAll();
        return $allAnimals;
    }
    // fonction pour récupérer tous les avis client depuis la base de données
    public function getAllAvisClients()
    {
        $avisClientsRepository = $this->entityManager->getRepository(AvisClients::class);
        $allAvisClients = $avisClientsRepository->findAll();
        return $allAvisClients;
    }
    // Fonction pour récupérer le dernier avis client depuis la base de données
    public function getLastAvisClient()
    {
        $avisClientsRepository = $this->entityManager->getRepository(AvisClients::class);
        $lastAvisClient = $avisClientsRepository->findOneBy([], ['id' => 'DESC']);
        return $lastAvisClient;
    }
    // fonction pour récupérer tous les commentaires habitats depuis la base de données
    public function getAllComHabitat()
    {
        $commentHabRepository = $this->entityManager->getRepository(CommentHabitat::class);
        $AllComHabitat = $commentHabRepository->findAll();
        return $AllComHabitat;
    }

    // Fonction pour récupérer le dernier commentaires habitat depuis la base de données
    public function getLastComHabitat()
    {
        $commentHabRepository = $this->entityManager->getRepository(CommentHabitat::class);
        $lastComHabitat = $commentHabRepository->findOneBy([], ['id' => 'DESC']);
        return $lastComHabitat;
    }
    // fonction pour récupérer tous les habitats client depuis la base de données
    public function getAllHabitats()
    {
        $animalRepository = $this->entityManager->getRepository(Habitat::class);
        $allHabitats = $animalRepository->findAll();
        return $allHabitats;
    }
    // fonction pour récupérer tous les mail clients depuis la base de données
    public function getAllMailsClients()
    {
        $mailClientsRepository = $this->entityManager->getRepository(MailClients::class);
        $allMailsClients = $mailClientsRepository->findAll();
        return $allMailsClients;
    }
    // Fonction pour récupérer le dernier mail client depuis la base de données
    public function getLastMailClient()
    {
        $mailClientsRepository = $this->entityManager->getRepository(MailClients::class);
        $lastMailClient = $mailClientsRepository->findOneBy([], ['id' => 'DESC']);
        return $lastMailClient;
    }
    // fonction pour récupérer tous les rapport animals client depuis la base de données
    public function getAllRapAnimals()
    {
        $RapportAnimalsRepository = $this->entityManager->getRepository(RapportAnimal::class);
        $AllRapAnimals = $RapportAnimalsRepository->findAll();
        return $AllRapAnimals;
    }
    // Fonction pour récupérer le dernier rapport animal depuis la base de données
    public function getLastRapAnimals()
    {
        $RapportAnimalsRepository = $this->entityManager->getRepository(RapportAnimal::class);
        $lastRapAnimals = $RapportAnimalsRepository->findOneBy([], ['id' => 'DESC']);
        return $lastRapAnimals;
    }
    // fonction pour récupérer tous les rapports nourriture depuis la base de données
    public function getAllRapNourriture()
    {
        $RapportNourritureRepository = $this->entityManager->getRepository(RapportNourriture::class);
        $AllRapportNourriture = $RapportNourritureRepository->findAll();
        return $AllRapportNourriture;
    }
    // Fonction pour récupérer le dernier rapport nourriture depuis la base de données
    public function getLastRapNourriture()
    {
        $RapportNourritureRepository = $this->entityManager->getRepository(RapportNourriture::class);
        $lastRapNourriture = $RapportNourritureRepository->findOneBy([], ['id' => 'DESC']);
        return $lastRapNourriture;
    }
    // Fonction pour récupérer tout les services
    public function getAllServices() {
        $ServicesRepository = $this->entityManager->getRepository(Services::class);
        $AllServices = $ServicesRepository->findAll();
        return $AllServices;
    }
    // Fonction pour récupérer tout les schedules
    public function getSchedules() {
        $SchedulesRepository = $this->entityManager->getRepository(Schedules::class);
        $Schedules = $SchedulesRepository->findAll();
        return $Schedules;
    }
}