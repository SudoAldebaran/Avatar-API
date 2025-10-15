# Projet Avatar - README
Projet réalisé dans le cadre de la soutenance de fin d’études de la Licence Informatique à Sorbonne Université.

## Description du projet
Ce projet consiste à développer un site web permettant aux utilisateurs de créer, personnaliser et gérer des avatars uniques au format SVG. Les utilisateurs peuvent personnaliser divers éléments (couleur de peau, yeux, nez, cheveux, etc...) via une interface intuitive, sauvegarder leurs avatars dans une bibliothèque personnelle, et les télécharger. 
Une API REST est également disponible pour permettre aux développeurs d’intégrer la récuperation des avatars utilisateurs dans leur application. 

## Acteurs :
- Utilisateurs individuels : Créent des avatars pour leurs profils (réseaux sociaux, jeux, etc.).
- Développeurs : Intègrent la création d’avatars dans leurs plateformes.
- Administrateurs : Gèrent la plateforme et les clés API.

## Fonctionnalités principales

### Création et personnalisation d’avatars :
- Choix des éléments : couleur de peau, type/couleur des yeux, nez, bouche, cheveux, sourcils, barbe, lunettes, accessoires, haut, arrière-plan.
- Aperçu en temps réel des modifications.


### Gestion des avatars :
- Sauvegarde dans une bibliothèque personnelle avec un nom personnalisé.
- Visualisation, modification ou suppression des avatars.


### Téléchargement : Exportation des avatars au format SVG.

### Authentification sécurisée :
- Inscription/connexion avec email et mot de passe (hachage, tokens).


### API REST :
- Endpoints pour créer, sauvegarder, récuperer, supprimer et télécharger des avatars.
- Sécurisée par des tokens d’authentification.


### Administration :
- Génération et gestion des clés API pour les clients.

### Technologies utilisées :

- Backend : Laravel 12 (PHP), MySQL 8
- Frontend : Blade, Tailwind CSS

### Structure du projet

- Base de données : avatar_complet, cle_apis, svg_elements, users
- Pages principales :
    - Connexion/Inscription
    - Mon compte
    - Personnalisation d’avatar
    - Bibliothèque d’avatars
    - Page d’administration (génération de clés API)

## Endpoints de l’API

| Méthode | Endpoint | Description | Contrôleur/Action | Middleware |
|---------|----------|-------------|------------------|------------|
| GET     | `/svg-elements` | Récupère la liste de tous les éléments SVG disponibles pour composer les avatars et les met en cache. | `SvgElementController@index` | Aucun |
| GET     | `/public-avatars` | Retourne une liste minimale de tous les avatars publics (accès protégé par clé API). | `AvatarApiController@allAvatarsMinimal` | `ApiKeyMiddleware` |
| GET     | `/user` | Renvoie les informations de l’utilisateur authentifié (profil). | Callback (Closure) | `auth:sanctum` |
| POST    | `/avatar_complet` | Crée ou sauvegarde un nouvel avatar complet pour l’utilisateur connecté, selon les paramètres envoyés. | `AvatarCompletController@store` | `auth:sanctum` |
| GET     | `/bibliotheque` | Récupère tous les avatars sauvegardés de l’utilisateur connecté. | `BibliothequeController@recuperer` | `auth:sanctum` |
| DELETE  | `/avatars/{id}` | Supprime un avatar précis (par son ID) de la bibliothèque de l’utilisateur connecté. | `BibliothequeController@delete` | `auth:sanctum` |
| POST    | `/register` | Crée un nouveau compte utilisateur avec email et mot de passe. | `AuthController@register` | Aucun |
| POST    | `/login` | Authentifie l’utilisateur et retourne un token d’accès (JWT/Sanctum). | `AuthController@login` | Aucun |
| POST    | `/logout` | Déconnecte l’utilisateur (invalide le token d’accès). | `AuthController@logout` | `auth:sanctum` |
| GET     | `/admin/api-keys` | Affiche la liste de toutes les clés API (réservé administrateur). | `ApiKeyController@index` | `AdminMiddleware` |
| POST    | `/admin/api-keys/generate` | Génère une nouvelle clé API (pour usage externe, réservé admin). | `ApiKeyController@generate` | `AdminMiddleware` |
| PATCH   | `/admin/api-keys/{id}/toggle` | Active ou désactive une clé API précise (par son ID, réservé admin). | `ApiKeyController@toggleStatus` | `AdminMiddleware` |
| DELETE  | `/admin/api-keys/{id}` | Supprime une clé API précise (par son ID, réservé admin). | `ApiKeyController@delete` | `AdminMiddleware` |


## Installation et configuration

Prérequis :
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js


### Étapes d’installation :

# Cloner le dépôt
git clone https://github.com/SudoAldebaran/Avatar-API.git

cd Avatar-API

# Installer les dépendances PHP
composer install

# Installer les dépendances frontend
npm install

# Configurer l’environnement
cp .env.example .env

Modifier .env pour configurer la connexion à la base de données et l’URL de l’application.

# Générer la clé d’application
php artisan key:generate

# Exécuter les migrations
php artisan migrate

# Alimenter les élements SVGs dans la base de données
php artisan db:seed --class=SvgFinalElementsSeeder

# Lancer le serveur
php artisan serve

## Tester la récuperation des Avatars sur un autre site

Lancer le fichier index.html dans le dossier "Test-Site-API"
