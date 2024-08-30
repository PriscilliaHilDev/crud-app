# Crud-App

Crud-App est une application web construite avec Laravel et Vue.js, utilisant Docker pour le déploiement. Ce guide vous aide à configurer et déployer l'application à l'aide de Docker.

## Prérequis

Avant de commencer, assurez-vous que vous disposez des outils suivants installés sur votre machine :

- **Docker** : [Télécharger et installer Docker](https://docs.docker.com/get-docker/)
- **Docker Compose** : [Télécharger et installer Docker Compose](https://docs.docker.com/compose/install/)

## Cloner le Dépôt

Pour obtenir le code source de l'application, clonez le dépôt GitHub :

```bash
git clone https://github.com/votre_nom_utilisateur/crud-app.git
cd crud-app.
```

## Cloner l'Image Docker

Si vous avez déjà l'image Docker, assurez-vous de l'avoir récupérée sur Docker Hub. Vous pouvez utiliser la commande suivante pour tirer l'image.

```bash
docker pull your_dockerhub_username/crud-app:latest
```
## Préparer l'Environnement
Avant de lancer les conteneurs, vous devez préparer votre environnement local en créant un fichier .env à la racine du projet. Ce fichier contient les variables d'environnement nécessaires pour que l'application fonctionne correctement.

Créez un fichier .env basé sur le modèle fourni.

```bash
cp .env.example .env
```
Ensuite, ouvrez le fichier .env et configurez les variables en fonction de votre environnement.

## Configurer la Base de Données (Docker)

Pour faire correspondre la configuration de la base de données avec celle définie dans votre fichier `docker-compose.yml`, suivez ces étapes :

### 1. Vérifiez le Fichier `docker-compose.yml`

Assurez-vous que votre fichier `docker-compose.yml` contient les informations suivantes pour le service MySQL :

```yaml
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: myrootpassword
      MYSQL_DATABASE: mydatabase
      MYSQL_USER: myuser
      MYSQL_PASSWORD: myuserpassword
    networks:
      - app-network
    ports:
      - "3306:3306"
```

Ajoutez dans votre .env les informations suivantes :

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=mydatabase
DB_USERNAME=myuser
DB_PASSWORD=myuserpassword
```

## Lancer les Conteneurs
Pour démarrer les conteneurs Docker et préparer l'application, exécutez la commande suivante :

```bash
docker-compose up --build
```
Cette commande construit et démarre les conteneurs définis dans votre fichier docker-compose.yml.

## Préparer le Projet
Une fois les conteneurs en cours d'exécution, accédez au conteneur de l'application Laravel.

### 1. Complétez l'Installation
Accédez au conteneur de l'application Laravel pour compléter l'installation des paquets nécessaires. Exécutez la commande suivante pour installer le client MySQL.

```bash
docker-compose exec app bash
```
```bash
apt-get update
```
```bash
apt-get install -y default-mysql-client
```
### 2. Tester la Connexion à la Base de Données
Après avoir installé le client MySQL, vous pouvez tester la connexion à la base de données MySQL en utilisant la commande suivante. Assurez-vous de remplacer myuser et myuserpassword par les identifiants appropriés définis dans votre fichier docker-compose.yml.

```bash
mysql -h db -u myuser -p
```

### 3. Installer les Dépendances PHP
Installez les dépendances PHP nécessaires via Composer :

```bash
composer install --no-dev --optimize-autoloader
```

### 4. Installer les Dépendances JavaScript
Installez les dépendances JavaScript via Yarn :

```bash
yarn install
```

### 5. Préparer le Stockage 
Créez le lien symbolique pour le stockage :

```bash
php artisan storage:link
```

### 6. Exécuter les Migrations
Exécutez les migrations pour préparer la base de données :

```bash
php artisan migrate
```

### 7. (Optionnel) Exécuter les Seeders
Si vous avez des seeders pour peupler la base de données avec des données de test, exécutez-les :

```bash
php artisan db:seed
```
### 8. Démarrer le Serveur de Développement
Pour que l'application Vue.js soit accessible en mode développement et que les modifications soient prises en compte automatiquement, démarrez le serveur de développement :

```bash
yarn run dev
```

## Accéder à l'Application
Après avoir préparé le projet et démarré le serveur de développement, vous pouvez accéder à l'application en ouvrant votre navigateur et en naviguant vers http://localhost

## Dépannage
Si vous rencontrez des problèmes, voici quelques étapes de dépannage :

### 1. Vérifiez les logs : 
Vous pouvez consulter les logs des conteneurs pour diagnostiquer les problèmes éventuels.

```bash
docker-compose logs
```

### 2. Vérifiez la connexion à la base de données :
 Assurez-vous que les paramètres dans le fichier .env correspondent aux paramètres définis dans docker-compose.yml.

### 3. Redémarrez les conteneurs : 
Parfois, un redémarrage des conteneurs peut résoudre des problèmes.

```bash
docker-compose down
docker-compose up --build
```

En suivant ces étapes, vous serez en mesure de lancer et de faire fonctionner votre projet Laravel et Vue.js avec Docker.
Ce guide détaillé vous permettra de cloner, configurer, et lancer votre application Lara