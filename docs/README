# Nom de l'application

ZOO ARCADIA v2
---
## Description

Le site **ZOO ARCADIA v2** est un site d'entrainement pour une évaluation.
---
## Fonctionnalités clés
- L'administrateur du site peu, via un dashboard, gérer la totalité des utilisateurs, des services, des habitats, des animaux et des horaires.
- Le Dashboard admin permet de voir le nombre de visite sur les animaux.
- Les Dashboard: L'administrateur, les employés et les vétérinaire possède chacun des dashboard pouvant gérer leur différente taches.
---
## Déploiement



### Prérequis
- Un serveur web (Apache, Nginx, etc.)
- Un accès FTP (FileZilla ou autre client FTP)
- PHP installé sur le serveur (version ^8.2)
- Une base de données MySQL configurée

### Étapes de déploiement

1. **Connexion au serveur FTP**
    - Téléchargez et installez [FileZilla](https://filezilla-project.org/).
    - Ouvrez FileZilla et connectez-vous à votre serveur FTP en utilisant les informations de connexion fournies par votre hébergeur (nom d'hôte, nom d'utilisateur, mot de passe, port).

2. **Transfert des fichiers**
    - Dans la fenêtre de gauche de FileZilla, naviguez jusqu'au répertoire local où se trouvent les fichiers de votre application.
    - Dans la fenêtre de droite, naviguez jusqu'au répertoire de votre serveur web où vous souhaitez déployer l'application (par exemple, `public_html` ou `www`).
    - Sélectionnez tous les fichiers et dossiers de votre application dans la fenêtre de gauche et faites-les glisser vers la fenêtre de droite pour les transférer sur le serveur.

3. **Configuration des variables d'environnement**
    - Accédez à la section de configuration de votre hébergeur pour définir les variables d'environnement nécessaires.
        - Ajoutez les variables nécessaires telles que `DATABASE_URL`, `APP_ENV`, etc.
        
        Exemple pour `DATABASE_URL` :
        ```env
        DATABASE_URL=mysql://nom_utilisateur:mot_de_passe@127.0.0.1:3306/nom_base_de_donnees
        ```

4. **Installation des dépendances**
    - Connectez-vous à votre serveur via SSH (si disponible) et accédez au répertoire de votre application.
    - Exécutez la commande suivante pour installer les dépendances :
    ```sh
    composer install --no-dev --optimize-autoloader
    ```

5. **Migrations de base de données**
    - Toujours via SSH, exécutez les commandes suivantes pour créer la base de données et appliquer les migrations :
    ```sh
    php bin/console doctrine:database:create
    mkdir -p C:\your-path\zoo-arcadia-v2\migrations
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    ```

6. **Configuration du serveur web**
    - Assurez-vous que votre serveur web pointe vers le répertoire `public` de votre application.
    - Pour Apache, vous pourriez avoir un fichier `.htaccess` déjà configuré. Sinon, créez-en un avec les paramètres suivants :
    ```apache
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    </IfModule>
    ```

7. **Permissions des dossiers**
    - Assurez-vous que les dossiers `var` et `public` ont les permissions appropriées pour être écrits par le serveur web :
    ```sh
    chmod -R 775 var public
    ```

8. **Vérification et tests**
    - Ouvrez votre navigateur et allez à l'URL de votre application.
    - Vérifiez que l'application fonctionne correctement et que toutes les fonctionnalités sont opérationnelles.


### Cloner le dépôt
```sh
git clone https://github.com/monarche05/zoo-arcadia-v2.git
cd zoo-arcadia-v2
