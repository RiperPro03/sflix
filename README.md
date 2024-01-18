# Site vitrine pour les série TV (Sflix)
Ce projet utilise une API de recherche de séries utilisant le modèle BM25.

C'est un projet universitaire avec pour sujet la réalisation d'un site vitrine pour les séries TV. En utilisant les fichiers de sous-titres de 126 séries, nous avons développé un backend robuste qui offre deux fonctionnalités clés : une fonction de recherche et une fonction de recommandation de séries.

# Auteurs

- [Christopher Asin](https://www.github.com/RiperPro03) [non-alternant]

- [Vincent Noguero](https://github.com/VINKYN) [non-alternant]

# Fonctionnalités

- **Recherche de séries** : Permet aux utilisateurs de trouver des séries en saisissant des mots-clés. Par exemple, les mots "crash", "avion", "île" retourne la série "Lost" en premiers résultats.
- **Recommandation de séries** : Suggère des séries similaires à une liste de séries favorites d'un utilisateur.
- **Gestion des utilisateurs** : BackOffice pour créer, modifier et supprimer des utilisateurs, et de créer, modifier et suprimer des séries favorites.
# Installation

## Prérequis

- Symfony 5.4
- Composer 2.6.6 +
- MySQL ou autre Base de données compatible.

Pour installer Symfony 5.4, exécutez :

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

Pour installer les dépendances nécessaires, exécutez :

```bash
## Naviguez vers le dossier du projet
cd sflix
```

```bash
## Installation de toutes les dépendances
composer install
```

Modifier le fichier `.env` pour mettre le lien de votre base de données MySQL ou autre :

**ATTENTION** il faut au préalable créer la Base de données (BD) nommé "sflix" comme dans l'exemple

```bash
###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://root:@127.0.0.1:3306/sflix?serverVersion=8&charset=utf8mb4"
#DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
###< doctrine/doctrine-bundle ###
```

```bash
# URL de la base de données ce compose comme cela
mysql://UTILISATEUR:MDP@ADRESSE/BD?serverVersion=8&charset=utf8mb4
```

Vous pouvez aussi changer l’adresse de l’api de recherche si vous avez configurer une autre adresse pour l’API de recherche.

```bash
URL_SearchAPI='http://127.0.0.1:8000/'
```

Une fois les modifications terminer faire une migration pour générer toutes les tables dans la base de données de l’application :

```bash
## Exécutez la migration pour générer toutes les tables dans la base de données de l’application
php bin/console doctrine:migrations:migrate
```

## Démarrage

Pour lancer le serveur web :

```bash
## Entrer dans le dossier du serveur
cd sflix
```

```bash
## Lancer le serveur
symfony server:start --port=8080
```

## Arrêt

Pour arrêter le serveur faire :

```bash
## Arrêter le serveur
symfony server:stop
```
