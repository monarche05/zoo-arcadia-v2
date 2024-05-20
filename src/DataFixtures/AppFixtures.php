<?php

namespace App\DataFixtures;

use App\Entity\Animals;
use App\Entity\AvisClients;
use App\Entity\CommentHabitat;
use App\Entity\Habitat;
use App\Entity\MailClients;
use App\Entity\RapportAnimal;
use App\Entity\RapportNourriture;
use App\Entity\Roles;
use App\Entity\Schedules;
use App\Entity\Services;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use IntlDateFormatter;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Role\Role;

class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory,
        private EntityManagerInterface $entityManager
        ){}

    public function load(ObjectManager $manager): void
    {

        $date = new DateTime();

        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::SHORT, 'Europe/Paris');
        $formatter->setPattern('HH:mm:ss');
        $heureFormatee = $formatter->format($date);
        $time = DateTime::createFromFormat('H:i:s', date($heureFormatee));

        $roleAdmin = new Roles();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $roleEmploye = new Roles();
        $roleEmploye->setName('ROLE_EMPLOYE');
        $manager->persist($roleEmploye);

        $roleVeterinaire = new Roles();
        $roleVeterinaire->setName('ROLE_VETERINAIRE');
        $manager->persist($roleVeterinaire);

        $manager->flush();
        // ajout de trois utilisateur admin,employe,veterinaire
        
        $admin = new User;
        $admin->setName("Jean");
        $admin->setFirstname("paul");
        $admin->setMail("jean.paul@mail.com");
        $admin->setPassword('admin');
        $admin->setRole($roleAdmin);
        $admin->setVerified(true);
        $manager->persist($admin);
        
        $employe = new User;
        $employe->setName("john");
        $employe->setFirstname("doe");
        $employe->setMail("john.doe@mail.com");
        $employe->setPassword('employe');
        $employe->setRole($roleEmploye);
        $employe->setVerified(true);
        $manager->persist($employe);
        
        $veterinaire = new User;
        $veterinaire->setName("Janne");
        $veterinaire->setFirstname("doe");
        $veterinaire->setMail("Janne.doe@mail.com");
        $veterinaire->setPassword('veterinaire');
        $veterinaire->setRole($roleVeterinaire);
        $veterinaire->setVerified(true);
        $manager->persist($veterinaire);
        
        $manager->flush();
        // ajout de 3 habitats
        $savane = new Habitat;
        $savane->setName("La savane");
        $savane->setDescription("Immergez-vous dans notre habitat de la savane au zoo: lions majestueux, girafes gracieuses et éléphants imposants vous attendent pour une aventure inoubliable au cœur de la faune africaine.");
        $savane->setListAnimals(["Dumbo"]);
        $savane->setImg(['savane.jpg']);
        $manager->persist($savane);

        $marais = new Habitat;
        $marais->setName("Le marais");
        $marais->setDescription("Explorez notre habitat de marais au zoo: des oiseaux exotiques, des reptiles fascinants et une végétation luxuriante vous transportent dans un écosystème unique et mystérieux. Une expérience immersive vous attend!");
        $marais->setListAnimals(["Josiane"]);
        $marais->setImg(['marais.jpg']);
        $manager->persist($marais);

        $jungle = new Habitat;
        $jungle->setName("La jungle");
        $jungle->setDescription("Plongez dans notre habitat jungle au zoo: une luxuriante canopée abrite une incroyable diversité de singes, de serpents colorés et d'oiseaux exotiques. Explorez ce monde sauvage et découvrez ses secrets cachés au détour de chaque liane. Une aventure inoubliable vous attend au cœur de la jungle.");
        $jungle->setListAnimals(["George"]);
        $jungle->setImg(['jungle.jpg']);
        $manager->persist($jungle);

        
        // ajout de 3 animaux
        $elephant = new Animals;
        $elephant->setName("Dumbo");
        $elephant->setSpecies("Éléphant");
        $elephant->setDescription('L\'éléphant est le plus grand mammifère terrestre, connu pour sa taille imposante, ses longues défenses et ses oreilles caractéristiques. Ces animaux majestueux sont réputés pour leur intelligence, leur mémoire exceptionnelle et leur comportement social complexe. Les éléphants vivent en groupes familiaux soudés, dirigés par une femelle âgée et expérimentée. Ils communiquent entre eux à l\'aide de sons, de gestes et de vibrations du sol. Malheureusement, la chasse pour l\'ivoire et la perte de leur habitat naturel ont entraîné une diminution dramatique de leur population, menaçant ainsi leur survie à long terme.');
        $elephant->setImg(["elephant.jpg", "elephant2.jpg"]);
        $savane->addAnimal($elephant);
        $elephant->setHabitat($savane);
        $manager->persist($elephant);
        
        $serpent = new Animals;
        $serpent->setName("George");
        $serpent->setSpecies("Serpent");
        $serpent->setDescription('Le serpent est un reptile fascinant et souvent mal compris. Il existe plus de 3 000 espèces de serpents dans le monde, allant des petites couleuvres aux grands pythons et anacondas. Les serpents sont caractérisés par leur corps allongé et dépourvu de membres, ainsi que par leur langue fourchue qu\'ils utilisent pour détecter les odeurs. Certains serpents sont venimeux et peuvent être dangereux pour l\'homme, tandis que d\'autres sont inoffensifs et même bénéfiques pour l\'environnement en tant que prédateurs de rongeurs et d\'autres petits animaux. Malgré leur réputation effrayante, les serpents sont des créatures complexes et adaptables qui jouent un rôle important dans l\'écosystème.');
        $serpent->setImg(["serpent.jpg"]);
        $jungle->addAnimal($serpent);
        $serpent->setHabitat($jungle);
        $manager->persist($serpent);
        
        $loutre = new Animals;
        $loutre->setName("Josiane");
        $loutre->setSpecies("Loutre");
        $loutre->setDescription('La loutre est un mammifère semi-aquatique réputé pour son pelage soyeux et sa démarche maladroite sur la terre ferme. Elle est dotée d\'une queue musclée et d\'une agilité exceptionnelle dans l\'eau, ce qui en fait un chasseur redoutable de poissons et de crustacés. Son intelligence et sa curiosité naturelle en font un animal fascinant à observer. La loutre est également connue pour son comportement ludique et sa capacité à utiliser des outils pour ouvrir les coquillages. Malheureusement, la destruction de son habitat et la pollution de l\'eau menacent sa survie dans certaines régions du monde.');
        $loutre->setImg(["Loutre.jpg", "Loutre2.jpg", "Loutre3.jpg"]);
        $marais->addAnimal($loutre);
        $loutre->setHabitat($marais);
        $manager->persist($loutre);
        
        $manager->flush();
        // Ajout des 3 service restauration,visite guidée et petit train
        $restauration = new Services;
        $restauration->setName("Restauration");
        $restauration->setDescription("Découvrez une expérience culinaire unique au cœur de la nature! Notre service de restauration au zoo offre des mets délicieux pour combler toutes les envies, des collations rapides aux repas savoureux. Profitez de notre ambiance conviviale et observez les animaux tout en dégustant nos spécialités. Venez vivre une aventure gastronomique inoubliable!");
        $restauration->setFree(false);
        $restauration->setImg(["restaurant.jpg", "vegan.jpg", "burger.jpg"]);
        $manager->persist($restauration);

        $visite = new Services;
        $visite->setName("Visite guidée");
        $visite->setDescription("Explorez les secrets de la faune avec nos visites guidées gratuites! Découvrez les habitats des animaux sous un nouvel angle et plongez dans leur monde fascinant. Nos guides expérimentés vous accompagnent pour une expérience éducative et immersive. Rejoignez-nous pour une aventure enrichissante au cœur de la vie sauvage!");
        $visite->setFree(true);
        $visite->setImg(["tour-guide.jpg"]);
        $manager->persist($visite);
        
        $train = new Services;
        $train->setName("Petit train");
        $train->setDescription("Embarquez pour une balade pittoresque à travers notre parc en petit train! Laissez-vous transporter à travers les merveilles de la nature tout en vous relaxant confortablement. Notre service de visite en train offre une manière unique et agréable de découvrir la diversité de notre zoo. Joignez-vous à nous pour une excursion mémorable au rythme tranquille du rail!");
        $train->setFree(false);
        $train->setImg(["train.jpg"]);
        $manager->persist($train);

        //ajout de 3 avis clients
        $avis1 = new AvisClients;
        $avis1-> setPseudo("Bibiche");
        $avis1-> setText("C'est un zoo génial");
        $avis1-> setValide(false);
        $avis1-> setDate($date);
        $avis1-> setNote(5);
        $manager->persist($avis1);

        $avis2 = new AvisClients;
        $avis2-> setPseudo("polo");
        $avis2-> setText("Ce zoo est très bien");
        $avis2-> setValide(true);
        $avis2-> setDate($date);
        $avis2-> setNote(4);
        $manager->persist($avis2);

        $avis3 = new AvisClients;
        $avis3-> setPseudo("fado");
        $avis3-> setText("C'est un super zoo, bang bang!");
        $avis3-> setValide(false);
        $avis3-> setDate($date);
        $avis3-> setNote(3);
        $manager->persist($avis3);

        //ajout de 3 message clients

        $msg1 = new MailClients;
        $msg1->setTitle("Trop de monde");
        $msg1->setMail("bibiche@mail.com");
        $msg1->setText("Il y à beaucoup trop de monde dans ce zoo");
        $msg1->setDate($date);
        $manager->persist($msg1);

        $msg2 = new MailClients;
        $msg2->setTitle("Très sale");
        $msg2->setMail("polo@mail.com");
        $msg2->setText("Le zoo est très sale !");
        $msg2->setDate($date);
        $manager->persist($msg2);

        $msg3 = new MailClients;
        $msg3->setTitle("Embauche");
        $msg3->setMail("fado@mail.com");
        $msg3->setText("Est ce que vous embauché ?");
        $msg3->setDate($date);
        $manager->persist($msg3);

        //ajout de 3 commentaire habitat

        $errorHabitat = "Aucun habitat existant trouvé.";
        $errorAnimal = "Aucun animal existant trouvé.";
        
        $savaneHabitat = $this->entityManager->getRepository(Habitat::class)->findOneBy(['name' => 'La savane']);
        $jungleHabitat = $this->entityManager->getRepository(Habitat::class)->findOneBy(['name' => 'La jungle']);
        $maraisHabitat = $this->entityManager->getRepository(Habitat::class)->findOneBy(['name' => 'Le marais']);
        $dumboAnimal = $this->entityManager->getRepository(Animals::class)->findOneBy(['name' => 'Dumbo']);
        $josianeAnimal = $this->entityManager->getRepository(Animals::class)->findOneBy(['name' => 'Josiane']);
        $georgeAnimal = $this->entityManager->getRepository(Animals::class)->findOneBy(['name' => 'George']);
        
        if ($savaneHabitat) {
            $com1 = new CommentHabitat;
            $com1->setHabitat($savaneHabitat);
            $com1->setDetail("Cet habitat est optimal");
            $com1->setState("parfait");
            $com1->setImprovement(false);
            $com1->setDate($date);
            $manager->persist($com1);
        } else {
            echo $errorHabitat + "2";
        }

        if ($jungleHabitat) {
            $com2 = new CommentHabitat;
            $com2->setHabitat($jungleHabitat);
            $com2->setDetail("Cet habitat à trop de végétation");
            $com2->setState("bon");
            $com2->setImprovement(true);
            $com2->setDate($date);
            $manager->persist($com2);
        } else {
            echo $errorHabitat + "2";
        }

        if ($maraisHabitat) {
            $com3 = new CommentHabitat;
            $com3->setHabitat($maraisHabitat);
            $com3->setDetail("Cet habitat me fait peur");
            $com3->setState("éxélent");
            $com3->setImprovement(false);
            $com3->setDate($date);
            $manager->persist($com3);
        } else {
            echo $errorHabitat + "3";
        }

        //ajout de 3 rapport animals et ajout de 3 rapport nourriture

        if ($dumboAnimal) {
            //rapport animal1
            $rapportAnimal1 = new RapportAnimal;
            $rapportAnimal1->setAnimal($dumboAnimal);
            $rapportAnimal1->setDetail("Cet animal vas bien");
            $rapportAnimal1->setNourriture("carotte");
            $rapportAnimal1->setQte("650");
            $rapportAnimal1->setDate($date);
            //rapport nourriture 1
            $rapportNourriture1 = new RapportNourriture;
            $rapportNourriture1->setAnimal($dumboAnimal);
            $rapportNourriture1->setNourriture("carotte");
            $rapportNourriture1->setQte("650");
            $rapportNourriture1->setDate($date);
            $rapportNourriture1->setTime($time);


            $manager->persist($rapportNourriture1);
            $manager->persist($rapportAnimal1);
        } else {
            echo $errorAnimal + "1";
        }

        if ($josianeAnimal) {
            //rapport animal2
            $rapportAnimal2 = new RapportAnimal;
            $rapportAnimal2->setAnimal($josianeAnimal);
            $rapportAnimal2->setDetail("Cet animal est mignon");
            $rapportAnimal2->setNourriture("palourde");
            $rapportAnimal2->setQte("200");
            $rapportAnimal2->setDate($date);
            //rapport nourriture 2
            $rapportNourriture2 = new RapportNourriture;
            $rapportNourriture2->setAnimal($josianeAnimal);
            $rapportNourriture2->setNourriture("palourde");
            $rapportNourriture2->setQte("200");
            $rapportNourriture2->setDate($date);
            $rapportNourriture2->setTime($time);


            $manager->persist($rapportNourriture2);
            $manager->persist($rapportAnimal2);
        } else {
            echo $errorAnimal + "2";
        }

        if ($georgeAnimal) {
            //rapport animal3
            $rapportAnimal3 = new RapportAnimal;
            $rapportAnimal3->setAnimal($georgeAnimal);
            $rapportAnimal3->setDetail("Cet animal est vivace");
            $rapportAnimal3->setNourriture("souris");
            $rapportAnimal3->setQte("300");
            $rapportAnimal3->setDate($date);
            //rapport nourriture 3
            $rapportNourriture3 = new RapportNourriture;
            $rapportNourriture3->setAnimal($georgeAnimal);
            $rapportNourriture3->setNourriture("souris");
            $rapportNourriture3->setQte("300");
            $rapportNourriture3->setDate($date);
            $rapportNourriture3->setTime($time);
 
 
            $manager->persist($rapportNourriture3);
            $manager->persist($rapportAnimal3);
        } else {
            echo $errorAnimal + "3";
        }

        //ajout des horaires

        $openingTime = new DateTime();
        $openingTime->setTime(9, 30, 0); // Définit l'heure d'ouverture à 09h30.

        $closingTime = new DateTime();
        $closingTime->setTime(19, 30, 0); // Définit l'heure de fermeture à 19h30.

        $schedules = new Schedules;
        $schedules->setDays(["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"]);
        $schedules->setOpeningTime($openingTime);
        $schedules->setClosingTime($closingTime);

        $manager->persist($schedules);

        $manager->flush();
    }
}
