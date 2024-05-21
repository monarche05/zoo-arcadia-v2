INSERT INTO Roles (name) VALUES
('ROLE_ADMIN'),
('ROLE_EMPLOYE'),
('ROLE_VETERINAIRE');

INSERT INTO User (name, firstname, mail, password, role_id, verified) VALUES
('Jean', 'paul', 'jean.paul@mail.com', 'admin', 1, TRUE),
('john', 'doe', 'john.doe@mail.com', 'employe', 2, TRUE),
('Janne', 'doe', 'Janne.doe@mail.com', 'veterinaire', 3, TRUE);

INSERT INTO Habitat (name, description, listAnimals, img) VALUES
('La savane', 'Immergez-vous dans notre habitat de la savane au zoo: lions majestueux, girafes gracieuses et éléphants imposants vous attendent pour une aventure inoubliable au cœur de la faune africaine.', '["Dumbo"]', '["savane.jpg"]'),
('Le marais', 'Explorez notre habitat de marais au zoo: des oiseaux exotiques, des reptiles fascinants et une végétation luxuriante vous transportent dans un écosystème unique et mystérieux. Une expérience immersive vous attend!', '["Josiane"]', '["marais.jpg"]'),
('La jungle', 'Plongez dans notre habitat jungle au zoo: une luxuriante canopée abrite une incroyable diversité de singes, de serpents colorés et d\'oiseaux exotiques. Explorez ce monde sauvage et découvrez ses secrets cachés au détour de chaque liane. Une aventure inoubliable vous attend au cœur de la jungle.', '["George"]', '["jungle.jpg"]');

INSERT INTO Animals (name, species, description, img, habitat_id) VALUES
('Dumbo', 'Éléphant', 'L\'éléphant est le plus grand mammifère terrestre, connu pour sa taille imposante, ses longues défenses et ses oreilles caractéristiques. Ces animaux majestueux sont réputés pour leur intelligence, leur mémoire exceptionnelle et leur comportement social complexe. Les éléphants vivent en groupes familiaux soudés, dirigés par une femelle âgée et expérimentée. Ils communiquent entre eux à l\'aide de sons, de gestes et de vibrations du sol. Malheureusement, la chasse pour l\'ivoire et la perte de leur habitat naturel ont entraîné une diminution dramatique de leur population, menaçant ainsi leur survie à long terme.', '["elephant.jpg", "elephant2.jpg"]', 1),
('George', 'Serpent', 'Le serpent est un reptile fascinant et souvent mal compris. Il existe plus de 3 000 espèces de serpents dans le monde, allant des petites couleuvres aux grands pythons et anacondas. Les serpents sont caractérisés par leur corps allongé et dépourvu de membres, ainsi que par leur langue fourchue qu\'ils utilisent pour détecter les odeurs. Certains serpents sont venimeux et peuvent être dangereux pour l\'homme, tandis que d\'autres sont inoffensifs et même bénéfiques pour l\'environnement en tant que prédateurs de rongeurs et d\'autres petits animaux. Malgré leur réputation effrayante, les serpents sont des créatures complexes et adaptables qui jouent un rôle important dans l\'écosystème.', '["serpent.jpg"]', 3),
('Josiane', 'Loutre', 'La loutre est un mammifère semi-aquatique réputé pour son pelage soyeux et sa démarche maladroite sur la terre ferme. Elle est dotée d\'une queue musclée et d\'une agilité exceptionnelle dans l\'eau, ce qui en fait un chasseur redoutable de poissons et de crustacés. Son intelligence et sa curiosité naturelle en font un animal fascinant à observer. La loutre est également connue pour son comportement ludique et sa capacité à utiliser des outils pour ouvrir les coquillages. Malheureusement, la destruction de son habitat et la pollution de l\'eau menacent sa survie dans certaines régions du monde.', '["Loutre.jpg", "Loutre2.jpg", "Loutre3.jpg"]', 2);

INSERT INTO Services (name, description, is_free, img) VALUES
('Restauration', 'Découvrez une expérience culinaire unique au cœur de la nature! Notre service de restauration au zoo offre des mets délicieux pour combler toutes les envies, des collations rapides aux repas savoureux. Profitez de notre ambiance conviviale et observez les animaux tout en dégustant nos spécialités. Venez vivre une aventure gastronomique inoubliable!', FALSE, '["restaurant.jpg", "vegan.jpg", "burger.jpg"]'),
('Visite guidée', 'Explorez les secrets de la faune avec nos visites guidées gratuites! Découvrez les habitats des animaux sous un nouvel angle et plongez dans leur monde fascinant. Nos guides expérimentés vous accompagnent pour une expérience éducative et immersive. Rejoignez-nous pour une aventure enrichissante au cœur de la vie sauvage!', TRUE, '["tour-guide.jpg"]'),
('Petit train', 'Embarquez pour une balade pittoresque à travers notre parc en petit train! Laissez-vous transporter à travers les merveilles de la nature tout en vous relaxant confortablement. Notre service de visite en train offre une manière unique et agréable de découvrir la diversité de notre zoo. Joignez-vous à nous pour une excursion mémorable au rythme tranquille du rail!', FALSE, '["train.jpg"]');

INSERT INTO AvisClients (pseudo, text, valide, date, note) VALUES
('Bibiche', 'C\'est un zoo génial', FALSE, NOW(), 5),
('polo', 'Ce zoo est très bien', TRUE, NOW(), 4),
('fado', 'C\'est un super zoo, bang bang!', FALSE, NOW(), 3);

INSERT INTO MailClients (title, mail, text, date) VALUES
('Trop de monde', 'bibiche@mail.com', 'Il y à beaucoup trop de monde dans ce zoo', NOW()),
('Très sale', 'polo@mail.com', 'Le zoo est très sale !', NOW()),
('Embauche', 'fado@mail.com', 'Est ce que vous embauché ?', NOW());

INSERT INTO CommentHabitat (habitat_id, detail, state, improvement, date) VALUES
(1, 'Cet habitat est optimal', 'parfait', FALSE, NOW()),
(3, 'Cet habitat à trop de végétation', 'bon', TRUE, NOW()),
(2, 'Cet habitat me fait peur', 'éxélent', FALSE, NOW());

INSERT INTO RapportAnimal (animal_id, detail, nourriture, qte, date) VALUES
(1, 'Cet animal vas bien', 'carotte', 650, NOW()),
(2, 'Cet animal est mignon', 'palourde', 200, NOW()),
(3, 'Cet animal est vivace', 'souris', 300, NOW());

INSERT INTO RapportNourriture (animal_id, nourriture, qte, date, time) VALUES
(1, 'carotte', 650, NOW(), '12:00:00'),
(2, 'palourde', 200, NOW(), '14:00:00'),
(3, 'souris', 300, NOW(), '15:00:00');

INSERT INTO Schedules (days, opening_time, closing_time) VALUES
('["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"]', '09:00:00', '18:00:00');