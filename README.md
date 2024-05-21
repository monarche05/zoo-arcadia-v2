# ZOO ARCADIA v2 Déploiement en local

---

## Description
Le site **ZOO ARCADIA v2** est un site d'entrainement pour une évaluation.

---

## Fonctionnalités clés
- L'administrateur du site peu, via un dashboard, gérer la totalité des utilisateurs, des services, des habitats, des animaux et des horaires.
- Le Dashboard admin permet de voir le nombre de visite sur les animaux.
- Les Dashboard: L'administrateur, les employés et les vétérinaire possède chacun des dashboard pouvant gérer leurs différentes taches.
- Un clients peu laisser un avis.
- Un client peu contacter le Zoo.

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

### Ajout du server Redis

Pour la fonctionnalité d'incrémentation du nombre de vue par animal il faut utiliser Redis.

Pour windows:

Vous devrez tout d'abord vous devrais installer WSL. Lancé votre invite de commande windows et taper la commande: 
```sh
    wsl --install
```

Une foi ceci installer vous devrais créer un "user" et un mot de passe pour votre distribution Linux WSL.
Il vous seras proposé de les entrer directement après avoir terminer l'installation.
Une foi que cela sera fait vous pourrez commencer l'installation de Redis.

```sh
    curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg

    echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list

    sudo apt-get update
    sudo apt-get install redis
```

Enfin, démarrez le serveur Redis comme ceci :
```sh
    sudo service redis-server start
```

Se connecter à Redis
Vous pouvez tester que votre serveur Redis est en cours d'exécution en vous connectant à la CLI Redis:

```sh
    redis-cli 
    127.0.0.1:6379> ping
```
Si votre serveur et bien actif il devrais vous répondre PONG!

Il ne vous resteras plus qu'à lier votre serveur Redis à votre application en ajoutant celui-ci dans le fichier .env: 
```sh
    URL_REDIS=redis://127.0.0.1:6379
```
Note: "127.0.0.1:6379" est l'adresse standard utiliser par Redis mais elle peu être modifier dans les redis.conf. Vérifier que ceci correspond à l'adresse de vôtre server Redis.


Pour plus d'info sur la procédure d'installation de WSL je vous invite à visiter https://learn.microsoft.com/en-us/windows/wsl/install
Pour plus d'information sur la procédure d'installation de Redis je vous invite à visiter https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/install-redis-on-windows/

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
