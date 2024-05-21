CREATE DATABASE IF NOT EXISTS zoo_arcadia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE animals (
    id INT AUTO_INCREMENT NOT NULL,
    habitat_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    img JSON NOT NULL COMMENT '(DC2Type:json)',
    description LONGTEXT NOT NULL,
    INDEX IDX_966C69DDAFFE2D26 (habitat_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE avis_clients (
    id INT AUTO_INCREMENT NOT NULL,
    pseudo VARCHAR(255) NOT NULL,
    text LONGTEXT NOT NULL,
    valide TINYINT(1) DEFAULT NULL,
    date DATE NOT NULL,
    note INT NOT NULL,
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE comment_habitat (
    id INT AUTO_INCREMENT NOT NULL,
    habitat_id INT NOT NULL,
    detail VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    improvement TINYINT(1) NOT NULL,
    date DATE NOT NULL,
    INDEX IDX_C844D0F8AFFE2D26 (habitat_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE habitat (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    list_animals JSON DEFAULT NULL COMMENT '(DC2Type:json)',
    img JSON NOT NULL COMMENT '(DC2Type:json)',
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE mail_clients (
    id INT AUTO_INCREMENT NOT NULL,
    title VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    text LONGTEXT NOT NULL,
    date DATE NOT NULL,
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE rapport_animal (
    id INT AUTO_INCREMENT NOT NULL,
    animal_id INT NOT NULL,
    detail VARCHAR(255) NOT NULL,
    nourriture VARCHAR(255) NOT NULL,
    qte INT NOT NULL,
    date DATE NOT NULL,
    INDEX IDX_BE0EED58E962C16 (animal_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE rapport_nourriture (
    id INT AUTO_INCREMENT NOT NULL,
    animal_id INT NOT NULL,
    nourriture VARCHAR(255) NOT NULL,
    qte INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    INDEX IDX_DDF82A628E962C16 (animal_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE roles (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE schedules (
    id INT AUTO_INCREMENT NOT NULL,
    days JSON NOT NULL COMMENT '(DC2Type:json)',
    opening_time TIME NOT NULL,
    closing_time TIME NOT NULL,
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE services (
    id INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    free TINYINT(1) NOT NULL,
    img JSON NOT NULL COMMENT '(DC2Type:json)',
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL,
    role_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) NOT NULL,
    INDEX IDX_8D93D649D60322AC (role_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;


ALTER TABLE animals 
    ADD CONSTRAINT FK_966C69DDAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id);

ALTER TABLE comment_habitat 
    ADD CONSTRAINT FK_C844D0F8AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id);

ALTER TABLE rapport_animal 
    ADD CONSTRAINT FK_BE0EED58E962C16 FOREIGN KEY (animal_id) REFERENCES animals (id);

ALTER TABLE rapport_nourriture 
    ADD CONSTRAINT FK_DDF82A628E962C16 FOREIGN KEY (animal_id) REFERENCES animals (id);

ALTER TABLE user 
    ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES roles (id);
