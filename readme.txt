Projet LO07 BlaBlaCar 2026

Etudiant 1 : KHARRAZ Sami
Etudiant 2 : Projet individuel

URL du projet sur dev-isi :
http://dev-isi.utt.fr/~kharrazs/lo07_tp/projet/

Configuration locale deja realisee :
- Base MySQL locale creee : blablacar2026
- SQL importe : database/blablacar2026.sql
- Connexion locale : 127.0.0.1:3306, utilisateur root, mot de passe vide

Installation :
1. Copier le dossier du projet sur le serveur dev-isi.utt.fr.
2. Importer database/blablacar2026.sql dans la base MySQL.
   Sous Windows/WAMP, utiliser le client MySQL en UTF-8, par exemple :
   mysql --default-character-set=utf8mb4 -u root -e "CREATE DATABASE IF NOT EXISTS blablacar2026 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; USE blablacar2026; SOURCE C:/chemin/vers/database/blablacar2026.sql;"
   Ne pas importer avec "Get-Content ... | mysql", car PowerShell peut casser les accents.
3. Modifier app/config/config.php si besoin :
   - APP_STUDENTS
   - APP_DEPLOY_URL
   - DB_HOST
   - DB_PORT
   - DB_NAME
   - DB_USER
   - DB_PASSWORD
4. Lancer index.php. Ce fichier initialise $_SESSION['login_id'] a -1 puis redirige vers app/router/router2.php?action=home.

Comptes de test principaux :
- Administrateur : boss / secret
- Conducteur : trisprior / secret
- Passager : calebprior / secret

Fonctionnalites implementees :
- Authentification, deconnexion et menu dynamique par role.
- Administrateur : utilisateurs, conducteurs, passagers, vehicules, villes.
- Conducteur : mes vehicules, mes trajets, ajout de trajet, passagers d'un trajet actif, cloture avec paiements.
- Passager : mes reservations, reservation d'un trajet actif.
- Examinateur : affichage des superglobales et ajout de 10 reservations aleatoires.
- Innovations : tableau de bord data et explication de l'amelioration MVC.
