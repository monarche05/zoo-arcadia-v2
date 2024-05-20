# ZOO ARCADIA v2 Déploiement en local

---

## Description
Le site **ZOO ARCADIA v2** est un site d'entrainement pour une évaluation.

---

## Fonctionnalités clés
- L'administrateur du site peu, via un dashboard, gérer la totalité des utilisateurs, des services, des habitats, des animaux et des horaires.
- Le Dashboard admin permet de voir le nombre de visite sur les animaux.
- Les Dashboard: L'administrateur, les employés et les vétérinaire possède chacun des dashboard pouvant gérer leur différente taches.

---

## Déploiement local

---

### Prérequis
- PHP installé sur l'environnement local (version ^8.2)
- MySQL
- Composer
### Cloner le dépôt
```sh
git clone https://github.com/monarche05/zoo-arcadia-v2.git
cd zoo-arcadia-v2
```
### Installation des dépendances
```sh
npm install
composer update
```
Créez vôtre fichier .env en ajoutant les variables d'environement:

```sh
APP_ENV=dev
APP_SECRET= "Votre clé secret"
DATABASE_URL="mysql://"user":"mot de passe"@127.0.0.1:3306/"database"?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
REDIS_URL=redis://localhost
```

Symfony génére automatiquement la clé secret à l'instalation mais dans nôtre cas vous devrais la générer.
Solution via php:

créez un fichier secretGen.php avec le contenue suivant:
```sh
<?php
echo bin2hex(random_bytes(16));
```

Dans le terminal éxécuter se fichier à l'aide de la commande: 
```sh
php secretGen.php
```

Vous obtiendrez un clé que vous pourrez utiliser pour vôtre variable d'environnement

---
## Création de la base de données
---
Pour cela vous devrez effectuer plusieur commande:
```sh
    php bin/console doctrine:database:create
    mkdir -p C:\your-path\zoo-arcadia-v2\migrations
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
```
Ces commande auront pour but de créer la base de données, de créer le fichier "migrations", créer la première migration, et migré les entités.
Note: Le path de la commande mkdir doit correspondre au chemin vers vôtre application.
---
### Ajout des DataFixtures
Pour tester votre base de données et l'application vous pouvez ajouter les DataFixtures à l'aide de la commande:
```sh
    php bin/console d:f:l
```

---

## Finalisation
Il se peu que vous ayez besoin de réinstaller le webpack-encore à l'aide de cet comande: 
```sh
    npm install --save @symfony/webpack-encore
```
Il ne vous reste plus qu'à lancer le server à l'aide de la commande: 
```sh
    symfony server:start
```
Si toute les oppération on était réalise avec succes vous devriez pouvoir accéder à l'application en local.
Vous pouvez également vous connecter au site en utilisant les données suivante:

---

### Compte administrateur
Mail:jean.paul@mail.com
Mot de passe: admin

---

### Compte employé
Mail: john.doe@mail.com
Mot de passe: employe

---

### Compte vétérinaire
Mail: Janne.doe@mail.com
Mot de passe: veterinaire
