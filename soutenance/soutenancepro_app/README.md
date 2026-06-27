# SoutenancePro

Application web de gestion des soutenances de fin d'études, développée avec **Symfony 6.4** (PHP 8.1+) et une base de données relationnelle (MySQL).

## Fonctionnalités

- Authentification et sécurité par rôles (`ROLE_ADMIN`, `ROLE_ENSEIGNANT`)
- Gestion des étudiants (CRUD + recherche par nom)
- Gestion des enseignants (CRUD)
- Gestion des salles (CRUD, contrainte de capacité > 0, code unique)
- Gestion des soutenances (programmation, modification, annulation)
  - Un étudiant ne peut avoir qu'une seule soutenance
  - Empêche deux soutenances dans la même salle au même moment
  - Empêche qu'un enseignant soit dans deux jurys à la même heure
- Tableaux de bord adaptés (Admin : statistiques globales ; Enseignant : ses soutenances/jurys)
- Gestion des utilisateurs (admin)

## Prérequis

- PHP >= 8.1 avec extensions : ctype, iconv, pdo_mysql
- Composer
- MySQL >= 8.0 (ou MariaDB)
- Symfony CLI (optionnel mais recommandé)

## Installation

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Configurer la base de données
# Modifier le fichier .env (ou créer un .env.local) avec vos paramètres MySQL :
# DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/soutenancepro?serverVersion=8.0&charset=utf8mb4"

# 3. Créer la base de données
php bin/console doctrine:database:create

# 4. Créer les tables (via migrations)
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 5. Charger les données de démonstration (compte admin + données de test)
composer require --dev doctrine/doctrine-fixtures-bundle   # si pas déjà fait
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur
symfony server:start
# ou
php -S 127.0.0.1:8000 -t public
```

L'application sera accessible sur `http://127.0.0.1:8000`.

## Comptes de démonstration (créés par les fixtures)

| Rôle        | Email                       | Mot de passe |
|-------------|-----------------------------|---------------|
| Administrateur | admin@soutenancepro.tg   | admin123      |
| Enseignant      | jean.koffi@univ.tg       | ens123        |

## Structure du projet

```
src/
  Entity/        -> Étudiant, Enseignant, Salle, Soutenance, User
  Repository/     -> requêtes Doctrine (recherche, contrôle des conflits)
  Controller/     -> contrôleurs (Security, Dashboard, Etudiant, Enseignant, Salle, Soutenance, User)
  Form/           -> formulaires Symfony
  Security/       -> Authenticator personnalisé
  DataFixtures/   -> jeu de données de démonstration
templates/        -> vues Twig (Bootstrap 5)
config/           -> configuration Symfony (sécurité, doctrine, routes)
```

## Commandes utiles

```bash
php bin/console cache:clear
php bin/console doctrine:schema:validate
php bin/console debug:router
```

## Auteur

Projet réalisé dans le cadre de l'examen final IT232 — Développement Web II — Année académique 2025-2026 — iP Net Institute of Technology.
