# Projet LO07 BlaBlaCar 2026

Application PHP/MySQL MVC realisee pour le projet LO07 2026.

Auteur : KHARRAZ Sami  
URL de deploiement : http://dev-isi.utt.fr/~kharrazs/lo07_tp/projet/

## Fonctionnalites

- Authentification par role avec menu dynamique.
- Administrateur : gestion des utilisateurs, conducteurs, passagers, vehicules et villes.
- Conducteur : consultation des vehicules, trajets, ajout de trajet, passagers d'un trajet actif et cloture avec paiements.
- Passager : consultation des reservations et reservation d'un trajet actif.
- Examinateur : affichage des superglobales et ajout de 10 reservations aleatoires.
- Innovations : tableau de bord statistique et page expliquant les ameliorations MVC.

## Architecture

```text
index.php
app/
  config/
  controller/
  model/
  public/css/
  router/
  view/
database/
  blablacar2026.sql
```

Toutes les actions passent par :

```text
app/router/router2.php?action=...
```

## Installation locale

1. Copier le projet dans le dossier web de WAMP, XAMPP ou equivalent.
2. Creer une base MySQL `blablacar2026`.
3. Importer le fichier SQL :

```bash
mysql --default-character-set=utf8mb4 -u root -e "CREATE DATABASE IF NOT EXISTS blablacar2026 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; USE blablacar2026; SOURCE database/blablacar2026.sql;"
```

4. Verifier la configuration dans `app/config/config.php`.
5. Ouvrir `index.php` dans le navigateur.

## Comptes de test

- Administrateur : `boss / secret`
- Conducteur : `trisprior / secret`
- Passager : `calebprior / secret`

## Notes techniques

- PHP MVC simple, sans Composer.
- Connexion MySQL avec PDO et requetes preparees.
- Sessions PHP basees sur `login_id`.
- Generation des nouveaux identifiants avec `MAX(id)+1`, car le SQL fourni ne contient pas d'`AUTO_INCREMENT`.
- Mots de passe en clair conserves pour rester compatible avec le fichier SQL fourni dans le sujet.
